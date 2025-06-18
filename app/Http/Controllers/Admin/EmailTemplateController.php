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

    public function edit($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return view('admin.email_templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = EmailTemplate::findOrFail($id);
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string'
        ]);
        $template->update($request->only('subject', 'body'));
        return redirect()->route('admin.email_templates.index')->with('success', 'Template updated successfully.');
    }
}
