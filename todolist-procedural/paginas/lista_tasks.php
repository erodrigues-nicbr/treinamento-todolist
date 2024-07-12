<h1>Minha Lista de Tarefas</h1>
    
    <form id="taskForm">
        <input type="text" id="newTask" placeholder="Nova Tarefa">
        <input type="submit" value="Adicionar">
    </form>

    <ul id="todolist">

        <?php foreach($tasks as $task ): ?>
        <li>
            <span><?= $task['nome']?></span>
        </li>
        <?php endforeach; ?>
    </ul>

    <script>
        document.getElementById('taskForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var newTask = document.getElementById('newTask').value;
            if (newTask) {
                var li = document.createElement('li');
                var span = document.createElement('span');
                span.textContent = newTask;
                var button = document.createElement('button');
                button.textContent = 'Concluir';
                button.onclick = function() {
                    completeTask(button);
                };
                li.appendChild(span);
                li.appendChild(button);
                document.getElementById('todolist').appendChild(li);
                document.getElementById('newTask').value = '';
            }
        });

        function completeTask(button) {
            var li = button.parentElement;
            var span = li.querySelector('span');
            span.classList.toggle('task-completed');
        }
    </script>