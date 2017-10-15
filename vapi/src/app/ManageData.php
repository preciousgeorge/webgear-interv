<?php

// app/ManageData

namespace App;

/**
* ManageData Class
*/
class ManageData
{
    /**
    * @var string $dirPath
    */
    private $dirPath;

    /**
    * @var string $file1
    */
    protected $file1;

    /**
    * @var string $file2
    */
    protected $file2;

    /**
    * Initialize json files and data path
    *
    */
    public function __construct()
    {
        $this->dirPath = __DIR__.'/../../database/';
        $this->file1 = 'input1.json';
        $this->file2 = 'input2.json';
    }

    /**
    * read Json from files
    *
    * @param $reset 
    *
    * @return string 
    */
    public function readJson($reset = '')
    {
        $content = $this->openFile($this->file1);
        if($this->checkFileRead($content)){
            $content = $this->openFile($this->file2);
            if($reset){
                $this->resetFile();
            }
        }else{
            $this->updateFile();

        }
        
        return $content;
    }


    /**
    * open and read file contents
    *
    * @param string
    * @return string Json
    */
    public function openFile($fileName)
    {
        $filePath = $this->dirPath.$fileName;
        return file_get_contents($filePath, 'r');
    }

    /**
    * Check if input1.json has read status
    * @param string jsonString
    *
    * @retun boolean
    */
    public function checkFileRead(string $fileContent) 
    {
        $arrayData = json_decode($fileContent);
        return property_exists(array_pop($arrayData), 'file_read');
    }
     
    /**
    * update input1.json file with a read status 
    */
    public function updateFile()
    {
        $update = ['file_read' => true];
        $filePath = $this->dirPath.$this->file1;
        $handle = @fopen($filePath, 'r+');
        if ($handle)
        {
            fseek($handle, 0, SEEK_END);
            if (ftell($handle) > 0)
            {
                fseek($handle, -1, SEEK_END);
                fwrite($handle, ',', 1);
                fwrite($handle, json_encode($update) . ']');
            }
            else
            {
                fwrite($handle, json_encode(array($update)));
            }
                fclose($handle);
        }
    }

    /**
    * resetFile
    * read given file and reset and delete its file_read property 
    *
    * @return boolean
    */
    public function resetFile()
    {
        $fileData = $this->openFile($this->file1);
        $fileName = $this->dirPath.$this->file1;
        $data = json_decode($fileData , true); 
        foreach ($data as $key => $value) {
            if (in_array('file_read', $value)) {
                unset($data[$key]);
            }
        }
        $fp = fopen($fileName, 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
        
    }
}