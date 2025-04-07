<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'backend.dashboard',
            'backend.admin.categories.index',
            'backend.admin.categories.store',
            'backend.admin.categories.edit',
            'backend.admin.categories.delete',
            'backend.admin.posts.index',
            'backend.admin.posts.store',
            'backend.admin.posts.edit',
            'backend.admin.posts.delete',
            'backend.admin.post.show',
            'backend.admin.pendingposts.index',
            'backend.admin.pendingposts.store',
            'backend.admin.pendingposts.edit',
            'backend.admin.pendingposts.delete',
            'backend.admin.pendingposts.approve',
            'backend.admin.pendingposts.deny',
            'backend.admin.tags.index',
            'backend.admin.tags.store',
            'backend.admin.tags.edit',
            'backend.admin.tags.delete',
            'backend.admin.sliders.index',
            'backend.admin.sliders.store',
            'backend.admin.sliders.edit',
            'backend.admin.sliders.update',
            'backend.admin.sliders.delete',
            'backend.admin.settings.index',
            'backend.admin.settings.edit',
            'backend.admin.settings.update',
            'backend.admin.settings.data',
            'backend.admin.abouts.index',
            'backend.admin.abouts.edit',
            'backend.admin.abouts.update',
            'backend.admin.abouts.data',
            'backend.admin.contacts.index',
            'backend.admin.contacts.store',
            'backend.admin.contacts.edit',
            'backend.admin.contacts.update',
            'backend.admin.contacts.delete',
            'backend.admin.newsletters.index',
            'backend.admin.newsletters.destroy',
            'backend.admin.contacts.indexform',
            'backend.admin.contacts.destroy',
            'backend.admin.comments.index',
            'backend.admin.comments.approve',
            'backend.admin.comments.destroy',
            'backend.admin.visitors.index',
            'backend.admin.visitors.destroy',
            'backend.admin.users.index',
            'backend.admin.users.store',
            'backend.admin.users.edit',
            'backend.admin.users.delete',
            'backend.admin.posts.report',
            'backend.admin.posts.export.pdf',
            'backend.admin.categories.report',
            'backend.admin.categories.export_pdf',
            'backend.admin.top_viewed_posts',
            'backend.admin.categories.top_viewed_posts.export_pdf',
            'backend.admin.roles.index',
            'backend.admin.roles.store',
            'backend.admin.roles.edit',
            'backend.admin.roles.delete'


        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
