# Task Management API

This project is a simple Task Management API built with Laravel, which allows users to register and manage tasks through API endpoints. Additionally, it includes a Blog Post CRUD API to create, read, update, and delete blog posts.

### Blog Post CRUD API
This API allows you to create, read, update, and delete blog posts.

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- MySQL
- Postman or any API testing tool

### Installation Steps
1. Clone the repository:
   ```bash
   git clone https://github.com/your-repo/task-management-api.git
   cd task-management-api
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Configure environment variables:
   - Copy `.env.example` to `.env`
   ```bash
   cp .env.example .env
   ```
   - Update database configurations in the `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

4. Run database migrations:
   ```bash
   php artisan migrate
   ```

5. Start the server:
   ```bash
   php artisan serve
   ```

## Folder Structure

```
├── app
│   ├── Http
│   │   └── Controllers
│   │       ├── UserController.php
│   │       ├── TaskController.php
│   │       └── RegisterController.php
│   └── Models
│       ├── User.php
│       └── Task.php
        └── Post.php
├── database
│   └── migrations
│       ├── 2024_12_18_000000_create_users_table.php
│       └── 2024_12_18_000001_create_tasks_table.php
        └── 2025_02_27_160609_create_posts_table.php
└── routes
    └── api.php
```

## API Endpoints

#### Endpoints:
- `GET /posts` - Retrieve a list of all blog posts.
- `GET /posts/:id` - Retrieve a single blog post by its ID.
- `POST /posts` - Create a new blog post.
- `PUT /posts/:id` - Update an existing blog post by its ID.
- `DELETE /posts/:id` - Delete a blog post by its ID.

#### Request and Response Formats:
- All requests and responses are in JSON format.

#### Example:
Request:
```json
{
    "title": "My New Blog Post",
    "content": "This is the content of my new blog post."
}
```

Response:
```json
{
    "id": 1,
    "title": "My New Blog Post",
    "content": "This is the content of my new blog post.",
    "createdAt": "2023-10-01T12:00:00Z"
}
```
### User Registration
Endpoint: `POST /api/register`

Request Body:
```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "password123"
}
```

Response:
```json
{
  "id": 1,
  "name": "Jane Doe",
  "email": "jane@example.com",
  "created_at": "2024-12-18T10:00:00Z"
}
```

### Add Task
Endpoint: `POST /api/tasks`

Request Body:
```json
{
  "title": "Finish Laravel Test"
}
```

Response:
```json
{
  "id": 1,
  "title": "Finish Laravel Test",
  "is_completed": false,
  "created_at": "2024-12-18T10:00:00Z"
}
```

### Mark Task as Completed
Endpoint: `PATCH /api/tasks/{id}`

Request Body:
```json
{
  "is_completed": true
}
```

Response:
```json
{
  "id": 1,
  "title": "Finish Laravel Test",
  "is_completed": true,
  "created_at": "2024-12-18T10:00:00Z"
}
```

### Get Pending Tasks
Endpoint: `GET /api/tasks/pending`

Response:
```json
[
  {
    "id": 2,
    "title": "Write Documentation",
    "is_completed": false,
    "created_at": "2024-12-18T11:00:00Z"
  }
]
```
  
## Testing APIs
You can test the API endpoints using Postman or any API testing tool.
## Well-commented Code

### UserController.php
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        return response()->json($user, 201);
    }
}
```

### TaskController.php
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    // Add a new task
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $task = Task::create([
            'title' => $validatedData['title'],
            'is_completed' => false,
        ]);

        return response()->json($task, 201);
    }

    // Mark a task as completed
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->is_completed = $request->input('is_completed');
        $task->save();

        return response()->json($task);
    }

    // Get pending tasks
    public function getPendingTasks()
    {
        $tasks = Task::where('is_completed', false)->get();
        return response()->json($tasks);
    }
}
```

### PostController.php
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    // Retrieve all blog posts
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
    }

    // Retrieve a single blog post by ID
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post);
    }

    // Create a new blog post
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::create($validatedData);
        return response()->json($post, 201);
    }

    // Update an existing blog post by ID
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());
        return response()->json($post);
    }

    // Delete a blog post by ID
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(null, 204);
    }
}
```

### User.php
```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
```

### Task.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'is_completed',
    ];
}
```

### Post.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content',
    ];
}
```

### 2024_12_18_000000_create_users_table.php
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
```

### 2024_12_18_000001_create_tasks_table.php
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
```

### 2025_02_27_160609_create_posts_table.php
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
```


