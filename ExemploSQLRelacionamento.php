<?php
require('baseDados.php');


class Insere extends DB{    
    
    //relacionamento entre a ordem de serviço e o cliente na base de dados
    public function rel_OrdemCliente($idORDEM, $idCLIENTE){
        $db = new DB();
        $stmt = $db->insert("baiaksoft_fenixinfo2.ordens_servico_has_cliente",[
            "ORDENS_SERVICO_idORDENS_SERVICO"=>$idORDEM,
            "CLIENTE_idCLIENTE"=>$idCLIENTE            
        ], true);
        $stmt->execute();
        
    }
    
    public function ordemServico($idCLIENTE, $DATA_INCL, $HORA_INCL, $PRAZO, $DATA_FIM, $HORA_FIM){
        $db = new DB();
        $stmt = $db->insert("baiaksoft_fenixinfo2.ordens_servico",[
            "DATA_INCL"=>$DATA_INCL,
            "HORA_INCL"=>$HORA_INCL,
            "PRAZO"=>$PRAZO,
            "DATA_FIM"=>$DATA_FIM,
            "HORA_FIM"=>$HORA_FIM
        ], true);
        $stmt->execute();
        
        //se o comando foi executado com sucesso
        if($stmt){
            //executa o SQL para pegar o ultimo ID inserido
            $stmt = $db->conn->prepare("SELECT LAST_INSERT_ID()");
            //metodo q executa o comando
            $stmt->execute();
            $idORDEM = $stmt->fetchColumn();
            //metodo de relacionamento ordem cliente
            $this->rel_OrdemCliente($idORDEM, $idCLIENTE);
            echo('Deu certo');
        } 
    }

}



if($_POST){
    $idCLIENTE = $_POST["idCLIENTE"];
    $DATA_INCL = $_POST["DATA_INCL"];
    $HORA_INCL = $_POST["HORA_INCL"];
    $PRAZO     = $_POST["PRAZO"];

    $insere = new Insere();

    $insere->ordemServico($idCLIENTE, $DATA_INCL, $HORA_INCL, $PRAZO, "", "");

}
?>