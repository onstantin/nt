<?php
/**
 * Created by PhpStorm.
 * User: Dusty
 * Date: 26.07.2016
 * Time: 18:43
 */

class Profile
{
    private $id;
    public $name;
    public $surname;
    public $city;
    public $photo;

    public function __construct($id)
    {
        $this->id = $id;

        if ($this->profileExists())
        {
            $this->loadProfile();
        }
        else
        {
            if ($this->getVkProfile())
            {
                $this->save();
            }
            else
            {
                $this->id = null;
            }
        }
    }

    private static function profilePath($id)
    {
        return "profile/".$id.".json";
    }

    private static function photoPath($id)
    {
        return "photo/".$id.".jpg";
    }

    private function profileExists()
    {
        if (file_exists(Profile::profilePath($this->id)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function loadProfile()
    {
        $content = file_get_contents(Profile::profilePath($this->id));
        $json = json_decode($content);

        $this->name = $json->name;
        $this->surname = $json->surname;
        $this->city = $json->city;
		
		if (file_exists(Profile::photoPath($this->id))) 
		{
			$this->photo = Profile::photoPath($this->id);
		}
		else
		{			
			$this->photo = $json->photo;
			$this->downloadPhoto();
		}
    }

    private function getVkProfile()
    {
        $content = file_get_contents("https://api.vk.com/method/users.get?user_ids={$this->id}&fields=photo_200,city");
        $json = json_decode($content);

        if (!isset($json->error))
        {
            $response = $json->response[0];

            $this->name = $response->first_name;
            $this->surname = $response->last_name;
            $this->city = City::getCityById($response->city);
            $this->photo = $response->photo_200;

            $this->downloadPhoto();
            return true;
        }
        else
        {
            return false;
        }
    }

    private function downloadPhoto()
    {
        return file_put_contents(Profile::photoPath($this->id), fopen($this->photo, 'r'));
    }

    private function save()
    {
        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'city' => $this->city,
            'photo' => $this->photo,
        ];

        $json = json_encode($array);
        return file_put_contents(Profile::profilePath($this->id),$json);
    }
	
	public function setNewName($name, $surname)
	{
		$this->name = $name;
		$this->surname = $surname;		
		$this->save();		
	}
	
	public function setNewPhoto($tmp_file)
	{
		$path = Profile::photoPath($this->id);
		move_uploaded_file($tmp_file, $path);
	}
}