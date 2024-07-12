<?php 
function addTask() {
    // Dados de exemplo
    $data = Request::getJsonData();
    if(!is_array($data) || empty($data) || !isset($data['nome']) || empty($data['nome']) ) {
        Response::responseJson(['message' => "Bad request"], 400   );
    }
    
    $data = [
        'nome' => $data['nome'],
        'prioridade' => $data['prioridade'],
    ];
    
    // Instancia o repositório de tasks
    $taksRepository = Utils::getRepository('tasks');
    $data = $taksRepository->insert($data);
    Response::responseJson($data);
}

function removeTask() {
    $id = intval(Request::getUriSegment(3));
    if(empty($id)) {
        Response::responseJson(['message' => "Bad request"], 400   );
    }
    
    // Instancia o repositório de tasks
    $taksRepository = Utils::getRepository('tasks');
    $taksRepository->delete($id);
    Response::responseJson(['message' => "Task removed"], 200);
}

function doneTask() {
    $id = intval(Request::getUriSegment(3));
    if(empty($id)) {
        Response::responseJson(['message' => "Bad request"], 400   );
    }
    
    // Instancia o repositório de tasks
    $taksRepository = Utils::getRepository('tasks');
    $taksRepository->update($id, ['done' => date('Y-m-d H:i:s')]);
    Response::responseJson(['message' => "Task done"], 200);
}

// Verifica o método da requisição
if(Request::isMethod('PUT')) doneTask();
if(Request::isMethod('POST')) addTask();
if(Request::isMethod('DELETE')) removeTask();

// Se não for nenhum dos métodos acima, retorna erro
Response::responseJson(['message' => "Method not allowed"], 405);