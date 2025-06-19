<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'slug' => 'welcome_email',
                'subject' => 'Welcome to {{site_name}}!',
                'body' => '<h1>Welcome to {{site_name}}, {{name}}!</h1>
<p>Thank you for joining our platform. We\'re excited to have you on board!</p>
<p>Here are some things you can do to get started:</p>
<ul>
<li>Complete your profile</li>
<li>Explore our features</li>
<li>Connect with other members</li>
</ul>
<p>If you have any questions, feel free to contact our support team.</p>
<p>Best regards,<br>The {{site_name}} Team</p>
<p><a href="{{site_url}}">Visit {{site_name}}</a></p>'
            ],
            [
                'slug' => 'password_reset',
                'subject' => 'Reset Your Password - {{site_name}}',
                'body' => '<h1>Password Reset Request</h1>
<p>Hello {{name}},</p>
<p>We received a request to reset your password for your {{site_name}} account.</p>
<p>If you requested this password reset, please click the link below to create a new password:</p>
<p><a href="{{reset_link}}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">Reset Password</a></p>
<p>If you did not request this password reset, please ignore this email. Your password will remain unchanged.</p>
<p>This link will expire in 60 minutes for security reasons.</p>
<p>Best regards,<br>The {{site_name}} Team</p>'
            ],
            [
                'slug' => 'review_invitation',
                'subject' => 'Share Your Experience - {{site_name}}',
                'body' => '<h1>We Value Your Feedback!</h1>
<p>Hello {{name}},</p>
<p>Thank you for choosing {{business_name}}. We hope you had a great experience!</p>
<p>We would love to hear about your experience. Your feedback helps us improve and helps other customers make informed decisions.</p>
<p><a href="{{review_link}}" style="background-color: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">Leave a Review</a></p>
<p>Your review will be displayed on our platform to help other customers.</p>
<p>Thank you for your time and feedback!</p>
<p>Best regards,<br>The {{site_name}} Team</p>'
            ],
            [
                'slug' => 'account_verification',
                'subject' => 'Verify Your Account - {{site_name}}',
                'body' => '<h1>Verify Your Account</h1>
<p>Hello {{name}},</p>
<p>Thank you for registering with {{site_name}}. To complete your registration, please verify your email address.</p>
<p><a href="{{verification_link}}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">Verify Email Address</a></p>
<p>If you did not create an account with us, please ignore this email.</p>
<p>This verification link will expire in 24 hours.</p>
<p>Best regards,<br>The {{site_name}} Team</p>'
            ],
            [
                'slug' => 'property_approved',
                'subject' => 'Your Property Has Been Approved - {{site_name}}',
                'body' => '<h1>Congratulations! Your Property is Now Live</h1>
<p>Hello {{name}},</p>
<p>Great news! Your property "{{property_name}}" has been approved and is now live on {{site_name}}.</p>
<p>Your property is now visible to potential customers and can start receiving reviews and ratings.</p>
<p><a href="{{property_link}}" style="background-color: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">View Your Property</a></p>
<p>You can now:</p>
<ul>
<li>Send review invitations to your customers</li>
<li>Manage your property details</li>
<li>View analytics and insights</li>
<li>Respond to customer reviews</li>
</ul>
<p>Thank you for joining {{site_name}}!</p>
<p>Best regards,<br>The {{site_name}} Team</p>'
            ],
            [
                'slug' => 'property_rejected',
                'subject' => 'Property Submission Update - {{site_name}}',
                'body' => '<h1>Property Submission Needs Attention</h1>
<p>Hello {{name}},</p>
<p>We have reviewed your property submission "{{property_name}}" and unfortunately, we cannot approve it at this time.</p>
<p><strong>Reason:</strong> {{rejection_reason}}</p>
<p>Don\'t worry! You can make the necessary changes and resubmit your property for approval.</p>
<p><a href="{{edit_property_link}}" style="background-color: #ffc107; color: black; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">Edit Property</a></p>
<p>If you have any questions about the rejection or need help with your submission, please contact our support team.</p>
<p>We appreciate your understanding and look forward to approving your property soon!</p>
<p>Best regards,<br>The {{site_name}} Team</p>'
            ]
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
