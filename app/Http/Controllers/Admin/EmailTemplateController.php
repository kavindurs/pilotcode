<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('admin.email_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.email_templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:email_templates',
            'subject' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        EmailTemplate::create($validated);

        return redirect()->route('admin.email_templates.index')
                        ->with('success', 'Email template created successfully!');
    }

    public function show($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return view('admin.email_templates.show', compact('template'));
    }

    public function edit($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return view('admin.email_templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = EmailTemplate::findOrFail($id);

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        $template->update([
            'subject' => $validated['subject'],
            'body' => $validated['body']
        ]);

        return redirect()->route('admin.email_templates.edit', $template->id)
                        ->with('success', 'Email template updated successfully!');
    }

    public function destroy($id)
    {
        $template = EmailTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('admin.email_templates.index')
                        ->with('success', 'Email template deleted successfully!');
    }
}
