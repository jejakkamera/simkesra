<?php

namespace App\Moodle;
use App\Models\UserTest;
use App\Models\User;
use App\Models\MoodleApi;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use App\Moodle\MoodleRest;

class MoodleUser
{
   public $moodleRest;

    public function __construct()
    {
        //
        $site = MoodleApi::find(1);
        $this->moodleRest = new MoodleRest($site->url, $site->token);
    }

    public function mod_quiz_get_quizzes_by_courses()
    {
       $site = MoodleApi::find(1);
      if($site){
        $client = new Client();
        try {
          $body = $this->moodleRest->request('mod_quiz_get_quizzes_by_courses',
                        []
                  );

dd($body);
                if ( isset($body['courses'][0])) {
                  return ['status'=>true,'info'=>'Connection successful. Course ID: ' . $body['courses'][0]['shortname']];
                } else {
                  return ['status'=>false,'info'=>'Connection failed. Error: Connection failed. Invalid response from Moodle'];
                }
            } catch (\Exception $e) {
              return ['status'=>false,'info'=>'Connection failed. Error: '.$e->getMessage()];
            }
      }else{
         return ['status'=>false,'info'=>'Connection failed. Error: Id Notfound'];
      }
    }

    public function core_course_get_courses()
    {
      $site = MoodleApi::find(1);
      if($site){
        $client = new Client();
        try {
          $body = $this->moodleRest->request('core_course_get_courses_by_field',
                        ['field' => 'id',
                        'value' => $site->courseid]
                  );
                if (isset($body['courses'][0])) {
                  return ['status'=>true,'info'=>'Connection successful. Course ID: ' . $body['courses'][0]['shortname']];
                } else {
                  return ['status'=>false,'info'=>'Connection failed. Error: Connection failed. Invalid response from Moodle'];
                }
            } catch (\Exception $e) {
              return ['status'=>false,'info'=>'Connection failed. Error: '.$e->getMessage()];
            }
      }else{
         return ['status'=>false,'info'=>'Connection failed. Error: Id Notfound'];
      }
    }

    public function get_site_info()
    {
      $site = MoodleApi::find(1);
      if($site){
        $client = new Client();
        try {

                $body = $this->moodleRest->request('core_webservice_get_site_info',[]);
                // dd($body);
                if (isset($body['siteid'])) {
                  return ['status'=>true,'info'=>'Connection successful. Site ID: ' . $body['sitename']];
                } else {
                  return ['status'=>false,'info'=>'Connection failed. Error: Connection failed. Invalid response from Moodle'];
                }
            } catch (\Exception $e) {
              return ['status'=>false,'info'=>'Connection failed. Error: '.$e->getMessage()];
            }
      }else{
         return ['status'=>false,'info'=>'Connection failed. Error: Id Notfound'];
      }
    }

    public function getUserIdByEmail($email)
    {
        $body = $this->moodleRest->request('core_user_get_users', [
                  'criteria' => [
                      [
                          'key' => 'username',
                          'value' => $email,
                      ],
                  ],
              ]);
              // dd($body['users'][0]['id']);
        if (isset($body['users'][0]['id'])) {
            return $body['users'][0];
        } else {
            return null;
        }
    }

    public function getQuizGrades($userId, $quizId)
    {
        return $this->moodleRest->request('mod_quiz_get_user_attempts', [
            'quizid' => $quizId,
            'userid' => $userId,
        ]);
    }

    public function create($userId)
    {
        // return 'Create User';
        $site = MoodleApi::find(1);
        $userTest = UserTest::where('user_id', $userId)->first();
        if (!$userTest) {
            // Jika user_id belum ada di UserTest, buat user di Moodle
            $user = User::find($userId);

            if ($user) {
                try {
                  $text_before_hyphen = explode('-',  $userId)[0];
                  $users = array('users' => array(array(
                      'username' => strtolower($user->email),
                      'password' => "PMB-".$text_before_hyphen,
                      'lastname' => $user->name,
                      'firstname' => "PMB-",
                      'email' => 'pmb' . strtolower($user->email),
                  )));
                // dd($users);
                $body = $this->moodleRest->request('core_user_create_users',$users);
                // dd($body);
                    if ( $body[0]['username'] == strtolower($user->email)) {
                        $userTest = new UserTest([
                            'user_id' => $userId,
                            'id_elearning' => $body[0]['id'],
                            'test_score' => null,
                            'is_passed_prodi' => 'pending',
                            'is_published' => 'pending',
                        ]);

                        $userTest->save();
                        $enrolments = array('enrolments' => array(array(
                                'roleid' => '5',
                                'userid' => $body[0]['id'],
                                'courseid' => $site->courseid,
                            )));
                        $this->moodleRest->request('enrol_manual_enrol_users',$enrolments);
                        return ['status'=>true,'info'=>'Connection successful. '];

                    }
                } catch (\Exception $e) {
                    return ['status'=>false,'info'=>'Connection failed. Error: '.$e->getMessage()];

                }
            }
        }else{
          return ['status'=>false,'info'=>'Connection failed. Error: Id Exist'];

        }


    }
}
