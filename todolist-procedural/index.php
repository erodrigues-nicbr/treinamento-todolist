<?php 

include_once "./essenciais/bootstrap.php"; 
$conn = connectDb();
$smtp = $conn->prepare("SELECT * FROM tasks;");
$smtp->execute();
$tasks = $smtp->fetchAll();
?>

<?php getElement("layout", [
    'title' => "Lista de tasks",
    'pageFile' => PAGE_DIR . "lista_tasks.php",
    "tasks" => $tasks
]); ?>
 