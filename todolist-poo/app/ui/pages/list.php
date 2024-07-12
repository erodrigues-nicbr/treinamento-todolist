<hr>
<h1>Lista de tarefas - PhpMySQL</h1>
<hr>
<?php include Utils::getComponentUi('legenda-prioridade'); ?>

<form action="/api/tasks" method="post">
    <div class="input-group">
        <input autofocus type="text" id="taskInput" placeholder="Adicionar nova tarefa... (max 61 char)" maxlength="61">
        <select id="taskPriority">
            <option class="prioridade-1" value="1">Baixa</option>
            <option class="prioridade-2" value="2">Média</option>
            <option class="prioridade-3" value="3">Alta</option>
        </select>
        <button onclick="onSubmit(event)" type="submit">Adicionar</button>
    </div>
</form>
<ul id="taskList">
    <?php
    // Instancia o repositório de tasks
    $taksRepository = Utils::getRepository('tasks');
    $tasks = $taksRepository->execute('select id, nome, prioridade, done from tasks order by done, created_at asc')->fetchAll();
    foreach ($tasks as $task) {
        // echo '<li id="task-item-'. $task['id'] . '" class="' . empty($task['done'] ) ? "pend" : "done" . '" >';
        echo '<li id="task-item-' . $task['id'] . '" class="' . (empty($task['done']) ? "pend" : "done") . '">';
        echo '<span class="prioridade-' . $task['prioridade'] . '">' . $task['nome'] . '</span>';
        echo '<div class="button-group">';
        echo '<button class="remove-action" onclick="removeTask(' . $task['id'] . ')">🗑️</button>';
        echo '<button class="done-action" onclick="doneTask(' . $task['id'] . ')">✅</button>';
        echo '</div>';
        echo '</li>';
    }
    ?>
</ul>
<script>

const prioridades = {
    1: 'Baixa',
    2: 'Média',
    3: 'Alta',
};

function onSubmit(event) {
    event.preventDefault();
    addTask();
}

function removeTask(id) {

    if( !confirm('Confirma a REMOÇÃO dessa tarefa?') ){
        return;
    }
    fetch('/api/tasks/' + id, {
            method: 'DELETE',
        })
        .then((response) => {

            if (response.status == 200) {
                const task = document.getElementById('task-item-' + id);
                task.remove(task.parentElement);
                document.getElementById('taskInput').focus();
            }

        });
}

function addTask() {
    fetch('/api/tasks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nome: document.getElementById('taskInput').value,
                prioridade: document.getElementById('taskPriority').value,
            }),
        })
        .then((response) => response.json())
        .then((task) => {
            if (!task || !task.nome) {
                return;
            }

            location.reload();
        });
}

function doneTask(id) {
    fetch('/api/tasks/' + id + '/done', {
            method: 'PUT',
        })
        .then((response) => {

            if (response.status == 200) {
                location.reload();
            }

        });
}
</script>