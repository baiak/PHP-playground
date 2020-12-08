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
    $stmt= $db->conn->prepare("SELECT 
    baiaksoft_fenixinfo2.cliente.TIPO as 'TIPO',
    baiaksoft_fenixinfo2.cliente.NOME as 'NOME',
    baiaksoft_fenixinfo2.cliente.CPF_CNPJ as 'CPF_CNPJ',
    baiaksoft_fenixinfo2.cliente.INSC_EST as 'INSC_EST',
    baiaksoft_fenixinfo2.tel.idTEL as 'idTEL',
    baiaksoft_fenixinfo2.tel.DESCR as 'DESCR',
    baiaksoft_fenixinfo2.tel.NUMERO as 'NUMERO',
    baiaksoft_fenixinfo2.endereco.LOGRADOURO as 'LOGRADOURO',
    baiaksoft_fenixinfo2.endereco.CEP as 'CEP',
    baiaksoft_fenixinfo2.endereco.CIDADE as 'CIDADE',
    baiaksoft_fenixinfo2.endereco.BAIRRO as 'BAIRRO',
    baiaksoft_fenixinfo2.endereco.ESTADO as 'ESTADO' 
    
    FROM
    baiaksoft_fenixinfo2.cliente,
    baiaksoft_fenixinfo2.tel,
    baiaksoft_fenixinfo2.endereco
    
    WHERE baiaksoft_fenixinfo2.cliente.idCLIENTE = :ID_CLIENTE 
    AND baiaksoft_fenixinfo2.tel.idCLIENTE = :ID_CLIENTE 
    AND baiaksoft_fenixinfo2.endereco.idCLIENTE = :ID_CLIENTE");


    $stmt->bindValue(":ID_CLIENTE", $ident);
    $stmt->execute();
    $arr = $stmt->fetchAll();

    if($arr > 0){
      foreach($arr as $key => $value){
          return($value[$atributo]);
      }
    }else{
        return false;
    }
 }

public function clienteTipo($ident){
    return $this->dadosCliente($ident, "TIPO"); 
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

public function clienteLogradouro($ident){
    return $this->dadosCliente($ident, "LOGRADOURO");
}

public function clienteCEP($ident){
    return $this->dadosCliente($ident, "CEP");
}

public function clienteBairro($ident){
    return $this->dadosCliente($ident, "BAIRRO");
}

public function clienteCidade($ident){
    return $this->dadosCliente($ident, "CIDADE");
}

public function clienteEstado($ident){
    return $this->dadosCliente($ident, "ESTADO");
}

public function clienteTelDescr($ident){
    return $this->dadosCliente($ident, "DESCR");
}

public function clienteTelNum($ident){
    return $this->dadosCliente($ident, "NUMERO");
}



public function exibeCliente($ident){
    echo("<b>".$this->clienteNome($ident)."</b>");
    if($this->clienteTipo($ident) == "juridica"){
        echo("&nbsp; - &nbsp;CNPJ: ".$this->clienteCPF_CNPJ($ident));
        echo("&nbsp; &nbsp; - &nbsp; &nbsp;Inscrição Estadual: ".$this->clienteInscEst($ident));
    }else if($this->clienteTipo($ident) == "fisica"){
       echo("&nbsp; - &nbsp;CPF: ".$this->clienteCPF_CNPJ($ident));
    }
    echo ("<br>");
    echo($this->clienteTelDescr($ident).' :'.$this->clienteTelNum($ident));
    echo("<br><br>Endereço:<br> ".$this->clienteLogradouro($ident)."&nbsp; - CEP: ".$this->clienteCEP($ident));
    echo("<br>".$this->clienteBairro($ident)." - ".$this->clienteCidade($ident). " - ".$this->clienteEstado($ident) );
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