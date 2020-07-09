<?php


namespace App\Controllers;


use App\Helpers;
use App\Logic\Validator;
use App\Model\Comments;
use Exception;

class CommentController
{
    public function allowedCommentsForOneCar(){
        $request = Helpers::GetJsonRequest();
        if (!empty($request)){
            $id = $request->id;
            $comments = Comments::find(['cars_id' => $id, 'allowed' => 1, 'deleted' => 0]);
           if (!empty($comments)){
               if (!is_array($comments)){
                   echo json_encode([$comments]);
               }else{
                   echo json_encode($comments);
               }
           }else{
               return false;
           }
        }
    }

    public function allowedComments(){
        if (Helpers::CheckPermission()){
            $comments = Comments::find(['allowed' => 1, 'deleted' => 0], 100);
            if (!empty($comments)){
                if (!is_array($comments)){
                    echo json_encode([$comments]);
                }else{
                    echo json_encode($comments);
                }
            }else{
                return false;
            }
        }
    }

    public function nonAllowedComments(){
        if (Helpers::CheckPermission()){
            $comments = Comments::find(['allowed' => 0, 'deleted' => 0]);
            if (is_array($comments)){
                echo json_encode($comments);
            }else{
                echo json_encode([$comments]);
            }
        }
    }

    public function deletedComments() {
        if (Helpers::CheckPermission()){
            $comments = Comments::find(['deleted' => 1]);
            if (is_array($comments)){
                echo json_encode($comments);
            }else{
                echo json_encode([$comments]);
            }
        }
    }

    public function allow() {
        if(Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                try {
                    Comments::update(
                        [
                            'id' => $request->id,
                            'allowed' => 1
                        ]
                    );

                    $backrequest = [
                        'message' => 'Uspešno odobren komentar!',
                        'status' => true
                    ];

                    echo json_encode($backrequest);
                }catch (Exception $exception){
                    $backrequest = [
                        'message' => 'Neuspešno odobrenje komentara!',
                        'status' => false
                    ];

                    echo json_encode($backrequest);
                }
            }
        }
    }

    public function disallow(){
        if(Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                try {
                    Comments::update(
                        [
                            'id' => $request->id,
                            'allowed' => 0
                        ]
                    );

                    $backrequest = [
                        'message' => 'Uspešno vraćen komentar!',
                        'status' => true
                    ];

                    echo json_encode($backrequest);
                }catch (Exception $exception){
                    $backrequest = [
                        'message' => 'Neuspešne izmene!',
                        'status' => false
                    ];

                    echo json_encode($backrequest);
                }
            }
        }
    }

    public function store() {
        $request = Helpers::GetJsonRequest();
        if (!empty($request)){
            $car_id = $request->car_id;
            $name = $request->name;
            $email = $request->email;
            $comment = $request->comment;
            if (empty($car_id) or empty($name) or empty($email) or empty($comment)){
                $backrequest = [
                    'message' => 'Obavezna polja su prazna!',
                    'status' => false
                ];

                echo json_encode($backrequest);
                exit();
            }

            try {
                $Comments = new Comments();
                $Comments->cars_id = $car_id;
                $Comments->name = $name;
                $Comments->email = $email;
                $Comments->comment = $comment;
                $Comments->created_at = date('d-m-Y H:i', time());
                $Comments->allowed = 0;
                $Comments->deleted = 0;
                $Comments->save();

                $backrequest = [
                    'message' => 'Uspešno dodat komentar!',
                    'status' => true
                ];

                echo json_encode($backrequest);
            }catch (Exception $exception){
                $backrequest = [
                    'message' => 'Neuspešno dodavanje komentara!',
                    'status' => false
                ];

                echo json_encode($backrequest);
            }
        }
    }

    public function destroy() {
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                try {
                    Comments::update(
                       [
                            'id' => intval($request->id),
                            'deleted' => 1
                       ]
                    );

                    $backrequest = [
                        'message' => 'Komentar je uspešno obrisan!',
                        'status' => true
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);
                }catch (Exception $exception){
                    $backrequest = [
                        'message' => 'Neuspešno brsianje!',
                        'status' => false
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);
                }
            }
        }
    }
}