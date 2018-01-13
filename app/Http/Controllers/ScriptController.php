<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScriptRequest;
use Illuminate\Http\Request;

class ScriptController extends Controller
{

    protected $token;

    public function __construct()
    {
        $this->token = config('vk-api.token');
    }

    public function get()
    {
        return view('main');
    }

    public function generate(Request $request)
    {
        while ($request->trues) {
            //получение информации о группе
            $rand = rand(0, 2);
            $well_get = $this->getGroupWall(config('vk-api.group')[$rand]);

            if ($well_get == false) {
                sleep(300);
            }

            if (isset($_POST['cpch'])) {
                $post = [];
                $post['id'] = $_POST['id'];
                $post['num'] = $_POST['cpch'];
                $dd = $this->captcha($post, $well_get, config('vk-api.message'));
            }
            //написание коммента
            $wall_createComment = $this->createComment($well_get, config('vk-api.message'));

            if (isset($wall_createComment['response']['cid'])) {
                $sleep = rand(5, 10);
                sleep($sleep);
            } elseif (isset($wall_createComment['error'])) {
                return $wall_createComment;
            }
            //получение спика друзей
            sleep(3);
            $friends_getRequests = $this->getFriendList();
            //добавление в друзья
            sleep(3);
            $friends_add = $this->addAllFriends($friends_getRequests);

//            $url = "https://api.vk.com/method/messages.send?user_id=72180866&message=привет помка&access_token=013aabd819cf1edfc9e07accb07d906560feec3a8b0580e6670a19577a3387df4006e7527054f1c7d2c39";
//            $url = "https://api.vk.com/method/$type2?$group&$acces_token";
            //https://oauth.vk.com/authorize?client_id=5650042&display=page&redirect_uri=https://oauth.vk.com/blank.html&scope=messages,wall&response_type=token&v=5.60!!!!!!!!!
//            $url = 'https://oauth.vk.com/authorize?client_id=5650042&display=page&redirect_uri=https://oauth.vk.com/blank.html&scope=offline,friends,photos,audio,video,pages,status,notes,messages,wall,groups&response_type=token&v=5.60!!!!!!!!!
            $sleep = rand(15, 25);
            sleep($sleep);
        }
    }

//получения инф о группе id поста и id группы
    private function getGroupWall($domain)
    {
        $wall_get = [];
        if (!empty($domain)) {

            $type = "wall.get";
            $group = 'domain=' . $domain;
            $url = "https://api.vk.com/method/$type?$group&$this->token&v=5.60";
            $url = preg_replace("/ /", "%20", $url);
            $bla = file_get_contents($url);
            $user = json_decode($bla, true);
            if (!empty($user['response']['items'])) {
                $get = $user['response']['items'][1]; //номер поста
                $wall_get = ['owner_id' => 'owner_id=' . $get['owner_id'], 'id' => 'post_id=' . $get['id']];
            }

        } else {
            $wall_get = false;
        }

        return $wall_get;
    }

//написание коммента
    private function createComment($well_get, $message)
    {
        $type = 'wall.createComment';
        $gid = $well_get['owner_id'];
        $postid = $well_get['id'];

        $url = "https://api.vk.com/method/$type?$gid&$postid&$message&$this->token";
        $url = preg_replace("/ /", "%20", $url);
        $bla = file_get_contents($url);
        $user = json_decode($bla, true);
        if (isset($user['error'])) {
            return $user;
            $cpch = 'captcha_sid=439343278641' . '&captcha_key=vq2mk';
            $url = "https://api.vk.com/method/$type?$gid&$postid&$message&$cpch&$this->token";
            $url = preg_replace("/ /", "%20", $url);
            $bla = file_get_contents($url);
            $user = json_decode($bla, true);
            return $user;
        }
        return $user;
    }

    //отправка капчи
    private function captcha($post, $well_get, $message)
    {
        $type = 'wall.createComment';
        $gid = $well_get['owner_id'];
        $postid = $well_get['id'];

        $cpch = 'captcha_sid=' . $post['id'] . '&captcha_key=' . $post['num'];
        $url = "https://api.vk.com/method/$type?$gid&$postid&$message&$cpch&$this->token";
        $url = preg_replace("/ /", "%20", $url);
        $bla = file_get_contents($url);
        $user = json_decode($bla, true);
        return $user;
    }

//получение списка заявок в друзья
    private function getFriendList()
    {
        $type = "friends.getRequests";
        $all = 'need_viewed=1&need_mutual=1';
        $url = "https://api.vk.com/method/$type?$this->token&$all&v=5.60";
        $url = preg_replace("/ /", "%20", $url);
        $bla = file_get_contents($url);
        $user = json_decode($bla, true);
        return $user;
    }

//добавление в друзья
    private function addAllFriends($response)
    {
        foreach ($response['response']['items'] as $key => $value) {
            $type = "friends.add";
            $id = 'user_id=' . $value['user_id'];
            $url = "https://api.vk.com/method/$type?$this->token&$id&v=5.60";
            $url = preg_replace("/ /", "%20", $url);
            $bla = file_get_contents($url);
            $user = json_decode($bla, true);
            $sleep = rand(4, 7);
            sleep($sleep);
        }
    }
}
