<?php
echo '<h1 style="text-align: center; margin-top: 40px;">Welcome to To DO App</h1>';

const TASKS_FILE = 'tasks.json';

function loadAllTasks(): array
{
    if (file_exists(TASKS_FILE)) {
        $alltasks = file_get_contents(TASKS_FILE);
        $tasks = json_decode($alltasks, true);
        if (count($tasks) > 0) {
            return $tasks;
        } else {
            return [];
        }
    } else {
        return [];
    }
}

$tasks = loadAllTasks();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task'])) {
        if (!empty($_POST['task'])) {
            $task = trim($_POST['task']);
            $tasks[] = [
                'name' => htmlspecialchars($task),
                'done' => false,
            ];
            file_put_contents(TASKS_FILE, json_encode($tasks, JSON_PRETTY_PRINT));
            header('Location:' . $_SERVER['PHP_SELF']);
            exit;
        }
    } elseif (isset($_POST['delete'])) {
        $index = $_POST['delete'];
        unset($tasks[$index]);
        file_put_contents(TASKS_FILE, json_encode(array_values($tasks), JSON_PRETTY_PRINT));
        header('Location:' . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['toggle'])) {
        $index = $_POST['toggle'];
        $tasks[$index]['done'] = !$tasks[$index]['done'];
        file_put_contents(TASKS_FILE, json_encode($tasks, JSON_PRETTY_PRINT));
        header('Location:' . $_SERVER['PHP_SELF']);
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css">
    <style>
        body {
            margin-top: 20px;
        }

        .task-card {
            border: 1px solid #ececec;
            padding: 20px;
            border-radius: 5px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .task {
            color: #888;
        }

        .task-done {
            text-decoration: line-through;
            color: #888;
        }

        .task-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        ul {
            padding-left: 20px;
        }

        button {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="task-card">
            <h1>To-Do App</h1>

            <!-- Add Task Form -->
            <form method="POST">
                <div class="row">
                    <div class="column column-75">
                        <input type="text" name="task" placeholder="Enter a new task" required>
                    </div>
                    <div class="column column-25">
                        <button type="submit" class="button-primary">Add Task</button>
                    </div>
                </div>
            </form>

            <!-- Task List -->
            <h2>Task List</h2>
            <ul style="list-style: none; padding: 0;">
                <!-- TODO: Loop through tasks array and display each task with a toggle and delete option -->
                <!-- If there are no tasks, display a message saying "No tasks yet. Add one above!" -->

                <?php if (empty($tasks)): ?>
                    <li>No tasks yet. Add one above!</li>
                <?php else: ?>
                    <?php foreach ($tasks as $index => $task): ?>
                        <li class="task-item">
                            <form method="POST" style="flex-grow: 1;">
                                <input type="hidden" name="toggle" value="<?= $index ?>">

                                <button type="submit" style="border: none; background: none; cursor: pointer; text-align: left; width: 100%;">
                                    <span class="task <?= $task['done'] ? 'task-done' : '' ?>">
                                        <?= $task['name'] ?>
                                    </span>
                                </button>
                            </form>

                            <form method="POST">
                                <input type="hidden" name="delete" value="<?= $index ?>">
                                <button type="submit" class="button button-outline" style="margin-left: 10px;">Delete</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</body>

</html>