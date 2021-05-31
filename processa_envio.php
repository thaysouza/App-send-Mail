<?php


require "./bibliotecas/PHPMailer/Exception.php";
require "./bibliotecas/PHPMailer/OAuth.php";
require "./bibliotecas/PHPMailer/PHPMailer.php";
require "./bibliotecas/PHPMailer/POP3.php";
require "./bibliotecas/PHPMailer/SMTP.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


class Mensagem
{
    private $para = null;
    private $assunto = null;
    private $mensagem = null;
    public $status = array('codigo_status' => null, 'descricao_status' => '');


    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function mensagemValida()
    {

        if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
            //se um dos campos estiver vazio, retornar false
            return false;
        }
        //se todos estiverem preenchidos, retorna true
        return true;
    }
}

$mensagem = new Mensagem();

$mensagem->__set('para', $_POST['para']);
$mensagem->__set('assunto', $_POST['assunto']/*  */);
$mensagem->__set('mensagem', $_POST['mensagem']);



/*
  echo "<pre>";
  print_r($mensagem);
  echo "</pre>";*/

///acessando o método validaMnesagem, para retornar uma mensagem ao usuário referente a condição feita no método
if (!$mensagem->mensagemValida()) {
    echo 'Mensagem não é válida';
    die();
}

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = false;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'meu email aqui';
    $mail->Password   = 'minha senha aqui';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    //Recipients
    $mail->setFrom('tayanesilvasouzas@gmail.com', 'Enviado e-mail para mim mesma');
    $mail->addAddress($mensagem->__get('para'));     //para quem a mensagem será enviada
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         
    $mail->addAttachment('./temp/fotos-tumblr-og.jpg');

    //Content
    $mail->isHTML(true);
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();


    $mensagem->status['codigo_status'] = 1;
    $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso!';
} catch (Exception $e) {


    $mensagem->status['codigo_status'] = 2;
    $mensagem->status['descricao_status'] = 'Não foi possivel enviado o e-mail! Por favor, tente novamente mais tarde. 
    Detalhe do erro: ' . $mail->ErrorInfo;
}

?>

<html>

<head>

    <meta charset="utf-8" />
    <title>App Mail Send</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>


<body>


    <div class="container">
        <div class="py-3 text-center">
            <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
            <h2>Send Mail</h2>
            <p class="lead">Seu app de envio de e-mails particular!</p>
        </div>


        <div class="row">
            <div class="col-md-12">


                <? if ($mensagem->status['codigo_status'] == 1) {  ?>


                    <div class="container">
                        <h1 class="display-4 text-success">Sucesso</h1>
                        <p><?= $mensagem->status['descricao_status']  ?></p>
                        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                    </div>

                <?  }  ?>



                <? if ($mensagem->status['codigo_status'] == 2) {  ?>

                    <div class="container">
                        <h1 class="display-4 text-success">Ops!</h1>
                        <p><?= $mensagem->status['descricao_status']  ?></p>
                        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                    </div>


                <?  }  ?>

            </div>
        </div>





    </div>

</body>

</html>