<?php
namespace smll\cms\framework\content\files;

use smll\framework\io\db\DB;

use smll\framework\settings\interfaces\ISettingsRepository;

use smll\framework\io\file\interfaces\IFileReference;

use smll\framework\io\file\File;

use smll\framework\utils\Guid;

use smll\framework\io\file\FileReference;

use smll\cms\framework\content\files\interfaces\IFileRepository;

class SqlFileRepository implements IFileRepository
{

    private $settings;
    private $connectionString;
    private $db;

    public function __construct(ISettingsRepository $settings)
    {
        $this->settings = $settings;

        $connectionStrings = $this->settings->get('connectionStrings');
        $this->connectionString = $connectionStrings['Default']['connectionString'];
        $this->db = new DB($this->connectionString);
    }

    public function getFileReference($file)
    {
        $db = $this->db;
        if ($file instanceof Guid) {
            $db->where(array('ident', '=', $file));
        } else if (is_string($file)) {
            $db->where(array('filename', '=', $file));
        }
        $ref = $db->get('file_reference');

        $reference = new FileReference();
        $reference->setIdent(Guid::parse($ref[0]->ident));
        $reference->setId($ref[0]->id);
        $reference->setFilename($ref[0]->filename);
        $reference->setFilesize($ref[0]->size);
        $reference->setMime($ref[0]->mime);


        $db->clearCache();
        $db->flushResult();

        return $reference;
    }
    public function setFileReference(IFileReference $fileReference)
    {

        $db = $this->db;

        $values = array(
                'ident' => $fileReference->getIdent(),
                'filename' => $fileReference->getFilename(),
                'size' => $fileReference->getFilesize(),
                'mime' => $fileReference->getMime()
        );

        $db->insert('file_reference', $values);

    }
    public function createFileReference(File $file, Guid $ident = null)
    {

        if (!isset($ident)) {
            $ident = Guid::createNew();
        }
        $ref = new FileReference();
        $ref->setIdent($ident);
        $ref->setFilename($file->getAbsolutePath());
        $ref->setMime($file->getMime());
        $ref->setFilesize($file->getSize());

        $this->setFileReference($ref);

        return $ref;
    }
    public function removeFileReference($ident)
    {
    }

}