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

  //funcao global que busca uma informacao especifica de um cliente no banco de dados
  public function dadosCliente($ident, $atributo){
    $db = new DB();
    $stmt= $db->conn->prepare("SELECT * FROM baiaksoft_fenixinfo2.cliente WHERE idCLIENTE = :ID_CLIENTE");
    $stmt->bindValue(":ID_CLIENTE", $ident);
    $stmt->execute();
    $arr = $stmt->fetchAll();

    if($arr > 0){
      foreach($arr as $key => $value){
          return($value[$atributo]);//atributo é o campo da entidade no banco de dados
      }
    }else{
        return false;
    }
 }

public function clienteNome($ident){
   return $this->dadosCliente($ident, "NOME");
}
public function clienteCPF_CNPJ($ident){
    return $this->dadosCliente($ident, "CPF_CNPJ");  
}
public function clienteInscEst($ident){
    return $this->dadosCliente($ident, "INSC_EST");
}


}

//comando para inserir dados na base de dados
$stmt = $db->insert("baiaksoft_fenixinfo2.tests",[
    "nome"=>"NOME",
    "sobrenome"=>"SOBRENOME"
], true);
$stmt->execute();

//se o comando foi executado com sucesso
if($stmt){
    //executa o SQL para pegar o ultimo ID inserido
    $stmt = $db->conn->prepare("SELECT LAST_INSERT_ID()");
    //metodo q executa o comando
    $stmt->execute();
    print_r($stmt->fetchColumn());
    echo('Deu boa!');
} 

?>