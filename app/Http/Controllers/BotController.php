<?php
/**
 * Created by PhpStorm.
 * User: tarciso
 * Date: 8/16/17
 * Time: 8:54 PM
 */
namespace App\Http\Controllers;

use CodeBot\CallSendApi;
use CodeBot\Message\Image;
use CodeBot\Message\Text;
use CodeBot\SenderRequest;
use CodeBot\Element\Button;
use CodeBot\Element\Product;
use CodeBot\TemplatesMessage\ButtonsTemplate;
use CodeBot\TemplatesMessage\GenericTemplate;
use CodeBot\TemplatesMessage\ListTemplate;
use CodeBot\WebHook;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function subscribe(){
        $webhook = new WebHook;
        $subscribe = $webhook->check(config('botfb.validationToken'));
        if(!$subscribe)
        {
            abort(403, 'Unauthorized action.');
        }

        return $subscribe;
    }

    public function receiveMessage(Request $request)
    {
        $sender = new SenderRequest();
        $senderId = $sender->getSenderId();
        $message = $sender->getMessage();

        $text = new Text($senderId);
        $callSenderApi = new CallSendApi(config('botfb.pageAccessToken'));

        $callSenderApi->make($text->message('Oii, eu sou um bot...'));
        $callSenderApi->make($text->message('Você digitou: '. $message));

        $message = new Image($senderId);
        $callSenderApi->make($message->message('http://fathomless-castle-56481.herokuapp.com/img/homer.gif'));

        $message = new ButtonsTemplate($senderId);
        $message->add(new Button('web_url', 'Code.Education', 'https://code.education'));
        $message->add(new Button('web_url', 'Google', 'http://www.google.com'));
        $callSenderApi->make($message->message('Que tal testarmos a abertura de um site?'));

        $button = new Button('web_url', null, 'https://angular.io/');
        $product = new Product('Produto 1', 'http://leonardohipolito.com/wp-content/uploads/2016/05/shield-with-beta.png', 'Curso de Angular', $button);
        $button = new Button('web_url', null, 'http://www.php.net/');
        $product2 = new Product('Produto 2', 'http://p9.storage.canalblog.com/95/52/388561/21464247.png', 'Curso de PHP', $button);

        $template = new ListTemplate($senderId);
        $template->add($product);
        $template->add($product2);

        $callSenderApi->make($template->message('qwe'));

        $button = new Button('web_url', null, 'https://angular.io/');
        $product = new Product('Produto 1', 'http://leonardohipolito.com/wp-content/uploads/2016/05/shield-with-beta.png', 'Curso de Angular', $button);

        $button = new Button('web_url', null, 'http://www.php.net/');
        $product2 = new Product('Produto 2', 'http://p9.storage.canalblog.com/95/52/388561/21464247.png', 'Curso de PHP', $button);

        $template = new GenericTemplate($senderId);
        $template->add($product);
        $template->add($product2);

        $callSenderApi->make($template->message('qwe'));

        return '';
    }
}