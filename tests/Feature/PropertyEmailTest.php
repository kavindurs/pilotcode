<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Property;
use App\Models\Category;
use App\Models\Subcategory;
use App\Mail\PropertyStatusMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;

class PropertyEmailTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test category and subcategory
        $category = Category::create([
            'name' => 'Test Category',
            'is_active' => true
        ]);

        Subcategory::create([
            'name' => 'Test Subcategory',
            'category_id' => $category->id,
            'is_active' => true
        ]);
    }

    /** @test */
    public function it_sends_approval_email_for_regular_property()
    {
        Mail::fake();

        $property = Property::create([
            'property_type' => 'web',
            'business_name' => 'Test Business',
            'business_email' => 'test@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'city' => 'Test City',
            'country' => 'Test Country',
            'status' => 'Not Approved',
            'category' => 1,
            'subcategory' => 1,
        ]);

        $response = $this->post(route('admin.properties.approve', $property->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'status' => 'Approved'
        ]);

        Mail::assertSent(PropertyStatusMail::class, function ($mail) use ($property) {
            return $mail->property->id === $property->id &&
                   $mail->action === 'approved' &&
                   $mail->originalEmail === 'test@example.com';
        });
    }

    /** @test */
    public function it_sends_approval_for_claim_email_with_clean_email()
    {
        Mail::fake();

        $property = Property::create([
            'property_type' => 'web',
            'business_name' => 'Test Business',
            'business_email' => 'test@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'city' => 'Test City',
            'country' => 'Test Country',
            'status' => 'Not Approved & Not Claimed',
            'category' => 1,
            'subcategory' => 1,
        ]);

        $response = $this->post(route('admin.properties.approve-for-claim', $property->id));

        $response->assertRedirect();

        // Verify the property was updated with modified email and status
        $property->refresh();
        $this->assertEquals('Not Claimed', $property->status);
        $this->assertStringContainsString('test@example.com', $property->business_email);
        $this->assertNotEquals('test@example.com', $property->business_email); // Should have numbers appended

        // Verify email was sent to the original clean email
        Mail::assertSent(PropertyStatusMail::class, function ($mail) {
            return $mail->action === 'approved_for_claim' &&
                   $mail->originalEmail === 'test@example.com'; // Should be clean email
        });
    }

    /** @test */
    public function it_sends_claimed_email_with_new_login_details()
    {
        Mail::fake();

        $property = Property::create([
            'property_type' => 'web',
            'business_name' => 'Test Business',
            'business_email' => 'test@example.com1234', // Simulating system-modified email
            'first_name' => 'John',
            'last_name' => 'Doe',
            'city' => 'Test City',
            'country' => 'Test Country',
            'status' => 'Not Claimed',
            'category' => 1,
            'subcategory' => 1,
        ]);

        $claimData = [
            'business_name' => 'Updated Business',
            'business_email' => 'newlogin@example.com',
            'property_type' => 'web',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'country' => 'Test Country',
            'city' => 'Test City',
            'zip_code' => '12345',
            'category' => '1',
            'subcategory' => '1',
            'status' => 'Approved',
            'password' => 'newpassword123',
        ];

        $response = $this->put(route('admin.properties.claim-update', $property->id), $claimData);

        $response->assertRedirect();

        // Verify email was sent to clean original email with new login details
        Mail::assertSent(PropertyStatusMail::class, function ($mail) {
            return $mail->action === 'claimed' &&
                   $mail->originalEmail === 'test@example.com' && // Clean email
                   $mail->newLoginEmail === 'newlogin@example.com' &&
                   $mail->newPassword === 'newpassword123';
        });
    }

    /** @test */
    public function it_cleans_email_addresses_correctly()
    {
        $property = Property::create([
            'property_type' => 'web',
            'business_name' => 'Test Business',
            'business_email' => 'test@example.com4371884587067342',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'city' => 'Test City',
            'country' => 'Test Country',
            'status' => 'Not Claimed',
            'category' => 1,
            'subcategory' => 1,
        ]);

        $mail = new PropertyStatusMail($property, 'claimed', null, 'newlogin@example.com', 'password123');

        // The constructor should clean the email automatically
        $this->assertEquals('test@example.com', $mail->originalEmail);
    }
}
