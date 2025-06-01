<?php

namespace App\Http\Controllers;

use App\Models\FileSection;
use App\Models\FileItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class FileController extends Controller
{
    public function index()
    {
        $sections = FileSection::withCount('items')->get();
        return view('file_management.index', compact('sections'));
    }

    public function storeSection(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:file_sections,name']);

        $section = FileSection::create($request->only('name'));

        return response()->json([
            'success' => true,
            'message' => 'Section created successfully',
            'section' => $section
        ]);
    }

    public function storeItem(Request $request)
    {
        $data = $request->validate([
            'file_section_id' => 'required|exists:file_sections,id',
            'parent_id' => 'nullable|exists:file_items,id,type,folder',
            'type' => 'required|in:folder,file',
            'name' => 'required|string|max:255',
            'file' => 'required_if:type,file|file|max:10240' // 10MB max
        ]);

        // Check for duplicate names in the same location
        $exists = FileItem::where('file_section_id', $data['file_section_id'])
            ->where('parent_id', $data['parent_id'] ?? null)
            ->where('name', $data['name'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'An item with this name already exists in this location.'
            ], 422);
        }

        if ($request->type === 'file' && $request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug(pathinfo($data['name'], PATHINFO_FILENAME)) . '.' . $extension;
            $path = $file->storeAs('uploads/files', $filename);
            $data['file_path'] = $path;
            $data['file_size'] = $file->getSize();
            $data['file_type'] = $file->getMimeType();
        }

        $item = FileItem::create($data);

        return response()->json([
            'success' => true,
            'message' => ucfirst($data['type']) . ' created successfully',
            'item' => $item
        ]);
    }

    public function updateItem(Request $request, $id)
    {
        $item = FileItem::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:folder,file'
        ]);

        // Check for duplicate names in the same location
        $exists = FileItem::where('file_section_id', $item->file_section_id)
            ->where('parent_id', $item->parent_id)
            ->where('name', $data['name'])
            ->where('id', '!=', $item->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'An item with this name already exists in this location.'
            ], 422);
        }

        $item->update($data);

        return response()->json([
            'success' => true,
            'message' => ucfirst($item->type) . ' renamed successfully',
            'item' => $item
        ]);
    }

    public function deleteItem($id)
    {
        $item = FileItem::findOrFail($id);

        if ($item->type === 'file' && $item->file_path) {
            Storage::delete($item->file_path);
        }

        // Delete all children if it's a folder
        if ($item->type === 'folder') {
            $this->deleteFolderContents($item);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => ucfirst($item->type) . ' deleted successfully'
        ]);
    }

    protected function deleteFolderContents($folder)
    {
        $children = FileItem::where('parent_id', $folder->id)->get();

        foreach ($children as $child) {
            if ($child->type === 'file' && $child->file_path) {
                Storage::delete($child->file_path);
            }

            if ($child->type === 'folder') {
                $this->deleteFolderContents($child);
            }

            $child->delete();
        }
    }

    public function moveItem(Request $request, $id)
    {
        $item = FileItem::findOrFail($id);

        $data = $request->validate([
            'parent_id' => 'nullable|exists:file_items,id,type,folder,file_section_id,'.$item->file_section_id
        ]);

        // Prevent moving a folder into itself or its children
        if ($item->type === 'folder' && $data['parent_id']) {
            $parentFolder = FileItem::find($data['parent_id']);

            if ($this->isChildFolder($parentFolder, $item)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot move a folder into itself or its subfolders.'
                ], 422);
            }
        }

        // Check for duplicate names in the new location
        $exists = FileItem::where('file_section_id', $item->file_section_id)
            ->where('parent_id', $data['parent_id'] ?? null)
            ->where('name', $item->name)
            ->where('id', '!=', $item->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'An item with this name already exists in the destination folder.'
            ], 422);
        }

        $item->update($data);

        return response()->json([
            'success' => true,
            'message' => ucfirst($item->type) . ' moved successfully',
            'item' => $item
        ]);
    }

    protected function isChildFolder($parent, $child)
    {
        if (!$parent) return false;

        if ($parent->id === $child->id) {
            return true;
        }

        return $this->isChildFolder($parent->parent, $child);
    }

    public function download($id)
    {
        $item = FileItem::where('type', 'file')->findOrFail($id);

        if (!$item->file_path || !Storage::exists($item->file_path)) {
            abort(404);
        }

        return Storage::download($item->file_path, $item->name . '.' . pathinfo($item->file_path, PATHINFO_EXTENSION));
    }

    public function folderList($sectionId, Request $request)
    {
        $section = FileSection::findOrFail($sectionId);
        $folderId = $request->query('folder_id');

        $currentFolder = null;
        if ($folderId) {
            $currentFolder = FileItem::where('type', 'folder')
                ->where('file_section_id', $sectionId)
                ->findOrFail($folderId);
        }

        $query = FileItem::where('file_section_id', $sectionId)
            ->where('parent_id', $folderId ?: null);

        $items = $query->orderBy('type', 'desc') // Folders first
            ->orderBy('name')
            ->get();

        return view('file_management.partials.folder_list', compact('section', 'items', 'currentFolder'));
    }

    public function folderOptions($sectionId, Request $request)
    {
        $excludeId = $request->query('exclude');

        $folders = FileItem::where('file_section_id', $sectionId)
            ->where('type', 'folder')
            ->when($excludeId, function($query) use ($excludeId) {
                $query->where('id', '!=', $excludeId);
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'folders' => $folders
        ]);
    }

    public function updateSection(Request $request, $id)
    {
        $section = FileSection::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:file_sections,name,'.$id
        ]);

        $section->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Section renamed successfully',
            'section' => $section
        ]);
    }

    public function downloadSelected(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required',
            'items.*.type' => 'required|in:section,folder,file',
            'section_id' => 'nullable|exists:file_sections,id'
        ]);

        $zip = new ZipArchive;
        $zipFileName = 'download_' . time() . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create ZIP file'
            ], 500);
        }

        try {
            foreach ($request->items as $item) {
                if ($item['type'] === 'section') {
                    $section = FileSection::findOrFail($item['id']);
                    $this->addSectionToZip($section, $zip, $section->name);
                } elseif ($item['type'] === 'folder') {
                    $folder = FileItem::where('type', 'folder')
                        ->where('file_section_id', $request->section_id)
                        ->findOrFail($item['id']);
                    $this->addFolderToZip($folder, $zip, $folder->name);
                } elseif ($item['type'] === 'file') {
                    $file = FileItem::where('type', 'file')
                        ->where('file_section_id', $request->section_id)
                        ->findOrFail($item['id']);

                    if ($file->file_path && Storage::exists($file->file_path)) {
                        $fileContents = Storage::get($file->file_path);
                        $zip->addFromString($file->name, $fileContents);
                    }
                }
            }

            $zip->close();

            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            if (file_exists($zipPath)) {
                unlink($zipPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error creating ZIP file: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function addSectionToZip($section, $zip, $basePath = '')
    {
        $items = FileItem::where('file_section_id', $section->id)
            ->whereNull('parent_id')
            ->get();

        foreach ($items as $item) {
            if ($item->type === 'folder') {
                $this->addFolderToZip($item, $zip, $basePath . '/' . $item->name);
            } elseif ($item->type === 'file' && $item->file_path && Storage::exists($item->file_path)) {
                $fileContents = Storage::get($item->file_path);
                $zip->addFromString($basePath . '/' . $item->name, $fileContents);
            }
        }
    }

    protected function addFolderToZip($folder, $zip, $basePath = '')
    {
        $items = FileItem::where('parent_id', $folder->id)->get();

        foreach ($items as $item) {
            if ($item->type === 'folder') {
                $this->addFolderToZip($item, $zip, $basePath . '/' . $item->name);
            } elseif ($item->type === 'file' && $item->file_path && Storage::exists($item->file_path)) {
                $fileContents = Storage::get($item->file_path);
                $zip->addFromString($basePath . '/' . $item->name, $fileContents);
            }
        }
    }

    public function deleteSelected(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required',
            'items.*.type' => 'required|in:section,folder,file'
        ]);

        try {
            foreach ($request->items as $item) {
                if ($item['type'] === 'section') {
                    $section = FileSection::findOrFail($item['id']);

                    // Delete all items in the section first
                    $items = FileItem::where('file_section_id', $section->id)->get();
                    foreach ($items as $itemToDelete) {
                        $this->deleteItemAndContents($itemToDelete);
                    }

                    $section->delete();
                } else {
                    $itemToDelete = FileItem::findOrFail($item['id']);
                    $this->deleteItemAndContents($itemToDelete);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Selected items deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting items: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function deleteItemAndContents($item)
    {
        if ($item->type === 'file' && $item->file_path) {
            Storage::delete($item->file_path);
        }

        if ($item->type === 'folder') {
            $children = FileItem::where('parent_id', $item->id)->get();
            foreach ($children as $child) {
                $this->deleteItemAndContents($child);
            }
        }

        $item->delete();
    }
}
