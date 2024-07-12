<?php if(!isset($pageFile) ) throw new Error("Não existe uma página para esse cara"); ?>

<!DOCTYPE html>
<html lang="pt-BR">
<?php getElement("head"); ?>

<body>
    <?php getElement("header"); ?>
    <?php include_once $pageFile; ?>
    <?php getElement("footer"); ?>
</body>
</html>
