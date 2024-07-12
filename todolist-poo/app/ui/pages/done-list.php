<hr>
    <h1>Lista de tarefas pendentes</h1>
<hr>
<?php include Utils::getComponentUi('legenda-prioridade'); ?>

<ul id="taskList">
    <?php
    // Instancia o repositório de tasks
    $taksRepository = Utils::getRepository('tasks');
    $tasks = $taksRepository->execute('select id, nome, prioridade, done from tasks where done is not null')->fetchAll();?>
    <table class="table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Prioridade</th>
                <th>Finalizado em</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
            <tr class="prioridade-<?php echo $task['prioridade'] ?>">
                <td><?php echo $task['nome']; ?></td>
                <td><?php echo Utils::getPrioridade($task['prioridade']); ?></td>
                <td><?php echo $task['done']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</ul>