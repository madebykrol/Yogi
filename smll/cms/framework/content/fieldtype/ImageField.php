<?php
namespace smll\cms\framework\content\fieldtype;

use smll\framework\io\file\FileReference;

use smll\cms\framework\content\utils\interfaces\IContentRepository;

use smll\framework\io\Request;

use smll\cms\framework\ui\fields\interfaces\IFieldRenderer;

use smll\framework\io\file\interfaces\IFileReference;

use smll\framework\utils\Guid;

use smll\framework\io\file\interfaces\IFileUploadManager;

use smll\cms\framework\content\fieldtype\interfaces\IFileFieldType;
use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;
use smll\cms\framework\content\files\interfaces\IFileRepository;
/**
 *
 * @author ksdkrol
 * [DefaultRenderer(smll\cms\framework\ui\fields\ImageFieldRenderer)]
 */
class ImageField extends BaseFieldType implements IFileFieldType
{

    protected $dataType = "linkGuid";
    private $manager = null;
    private $maxFileSize = '8MB';
    /**
     * @var IFileRepository
     */
    private $fileRepository = null;


    public function renderField($data, $parameters = null)
    {

        $output = '';
        $r = new Request();
        $i = 0;

        if ($data != null) {
            if (is_array($data)) {
                $i = 0;
                foreach ($data as $d) {
                    if ($d instanceof IFileReference) {
                        $output .= '<img src="'
                                .$r->getApplicationRoot()
                                    .'/'.$d->getFilename()
                                    .'" width="100" height="100"/>
                                <a href="#" class="btn btn-confirm" data-remove-resource="">
                                    <i class="icon-remove-sign"></i>
                                </a>';
                    }
                    $i++;
                }
            } else {
                $i = 1;
                if ($data instanceof IFileReference) {
                     
                    $output .= '<img src="'.$r->getApplicationRoot().'/'.$data->getFilename().'" width="100" height="100"/><a href="#" class="btn btn-confirm"><i class="icon-remove-sign"></i></a>';
                } else {
                    $output .= '<input name="'.$this->name.'[]" type="file" value="'.$data.'" id="input-'.$this->name.'"/>';
                }
            }
        }
        if ($i < $this->getFieldSettings()->get('maxInputValues') || $this->getFieldSettings()->get('maxInputValues') == 0){
            $output .= '
                    <input name="'.$this->name.'['.$i.']" type="file" value="" id="input-'.$this->name.'"/>';
        }



        return $output;

    }
    public function validateField($data, $parameters = null)
    {

        /**
         * @todo Make sure that this validates the file(s) correctly.
         */
        return true;
    }

    public function processData($data, $index = 0)
    {
        // If the manager has work todo, we assume this is a post request.
        if ($this->manager->hasFilesInPipe($this->name)) {
            if ($data != null) {
                $file = $this->manager->processFile($this->name, $index);
            }
            if ($file != null) {
                return $this->fileRepository->createFileReference($file, Guid::createNew());
            } else {
                return null;
            }
        } else {
             
            if (is_array($data)){
                foreach ($data as $index => $d) {
                    if (($guid = Guid::parse($d)) != null) {
                        $data[$index] = $this->fileRepository->getFileReference($guid);
                    }
                }
            } else {
                if (($guid = Guid::parse($data)) != null) {

                    $data = $this->fileRepository->getFileReference($guid);
                }
            }
        }

        return $data;
    }

    public function setFileUploadManager(IFileUploadManager $manager)
    {
        $this->manager = $manager;
    }

    public function setFileRepository(IFileRepository $repository)
    {
        $this->fileRepository = $repository;
    }
}