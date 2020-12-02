<?php
require("baseDados.php");
//exemplo de uso da classe da base de dados


class SearchClientes extends DB{
    
    //gera uma listbox com todos os clientes da base de dados
public function listBox(){
    $db = new DB();
    $stmt = $db->conn->prepare("SELECT * FROM baiaksoft_fenixinfo2.cliente ORDER BY NOME ASC");
    
    $stmt->execute();
    $arr = $stmt->fetchAll();

    echo('<select name="cliente" id="cliente">');
     foreach($arr as $key => $value){
         echo('<option value="'.$value["idCLIENTE"].'">'.$value["NOME"].'</option>');
     }
     echo( '</select>'); 
    }  


    //carrega apenas um cliente, se nao existir, gera um listbox com todos os clientes
public function tkCliente($ident){
    $db = new DB();
    $stmt= $db->conn->prepare("SELECT NOME FROM baiaksoft_fenixinfo2.cliente WHERE idCLIENTE = :ID_CLIENTE");
    $stmt->bindValue(":ID_CLIENTE", $ident);
    $stmt->execute();
    $arr = $stmt->fetch();
     
    if($arr > 0){ //se houver registros no array, faz o foreach
          
          foreach($arr as $key => $value){
              echo ($value);
            } 
    }else{
        //se nao,retorna o metodo com a listbox dos clientes
        return ($this->listBox());
    }
 
  }


}

?>