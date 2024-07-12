
<div class="legenda-prioridade">
    <p>
        <?php foreach( Utils::getPrioridades() as $key => $value ): ?>
            <span  class="prioridade-<?php echo $key ?>"><?= $value ?></span>
        <?php endforeach; ?>
    </p>
</div>