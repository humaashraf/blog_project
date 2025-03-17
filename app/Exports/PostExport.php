<?php
namespace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class PostExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Post::select('id', 'title', 'author_id', 'created_at')->get();
    }

    public function headings(): array
    {
        return ["ID", "Title", "Author", "Created At"];
    }
}
