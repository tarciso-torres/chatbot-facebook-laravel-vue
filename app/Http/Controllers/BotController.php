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
        $callSenderApi->make($text->message($message->message('http://fathomless-castle-56481.herokuapp.com/img/homer.gif')));

        return '';
    }
}