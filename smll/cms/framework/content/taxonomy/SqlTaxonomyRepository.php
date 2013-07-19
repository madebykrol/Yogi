<?php
namespace smll\cms\framework\content\taxonomy;

use smll\framework\utils\HashMap;

use smll\framework\io\db\DB;

use smll\cms\framework\content\taxonomy\interfaces\ITaxonomyTerm;

use smll\cms\framework\content\taxonomy\interfaces\IVocabulary;

use smll\framework\di\interfaces\IService;

use smll\framework\settings\interfaces\ISettingsRepository;

use smll\cms\framework\content\taxonomy\interfaces\ITaxonomyRepository;

class SqlTaxonomyRepository implements ITaxonomyRepository {
	
	private $settings = null;
	private $db;
	
	public function __construct(ISettingsRepository $settings) {
		$this->settings = $settings;
		
		$connectionStrings = $this->settings->get('connectionStrings');
		$this->connectionString = $connectionStrings['Default']['connectionString'];
		$this->db = new DB($this->connectionString);
	}
	
	public function getVocabularyByName($vocabulary) {
		$vocab = $this->db->query('SELECT * FROM taxonomy_vocabulary WHERE name=?', $vocabulary);
		$this->db->flushResult(true);
		if(isset($vocab[0])) {
			$vocab = $vocab[0];
		
			$vocabulary = new Vocabulary();
			$vocabulary->setDescription($vocab->description);
			$vocabulary->setName($vocab->name);
			
			$terms = new HashMap();
			
			$tmpTerms = $this->getTermsFromVocabulary($vocab->id);
			if(is_array($tmpTerms)) {
				foreach($tmpTerms as $tmpTerm) {
					$term = new Term();
					$term->setDescription($tmpTerm->description);
					$term->setTitle($tmpTerm->term);
					$term->setId($tmpTerm->id);
					$terms->add($tmpTerm->id,$term);
				}
			}
			$vocabulary->setTerms($terms);
			return $vocabulary;
			
		} else {
			return null;
		}
	}
	public function addVocabulary(IVocabulary $vocabulary) {
		$vocab = array(
			'name' => $vocabulary->getName(),
			'description' => $vocabulary->getDescription()		
		);
		
		if($vocabulary->getId()==null) {
			$this->db->insert('taxonomy_vocabulary', $vocab);
		}
		$id = $this->db->getLastInsertId();
		if($id != null) {
			return $id;
		}
		else {
			return false;
		}
	}
	public function removeVocabulary(IVocabulary $vocabulary) {}
	
	public function getAllVocabularies() {
		$vocabs = $this->db->query('SELECT * FROM taxonomy_vocabulary');
		$this->db->flushResult(true);
		
		return $vocabs;
	}
	
	public function addTaxonomyTerm(ITaxonomyTerm $term, $vocabularyId) {
		$term = array(
			'fkTaxonomyVocabularyId' => $vocabularyId,
			'term' => $term->getTitle(),
			'description' => $term->getDescription(),
			'parent' => $term->getParent(),
			'short' => $term->getShort()
		);
		
		$this->db->insert('taxonomy_term', $term);
		
	}
	
	public function removeTaxonomyTerm(ITaxonomyTerm $term, $vocabularyId) {}
	public function getTermsFromVocabulary($vocabularyId) {
		$terms = $this->db->query('SELECT * FROM taxonomy_term WHERE fkTaxonomyVocabularyId = ?', $vocabularyId);
		$this->db->flushResult(true);
		return $terms;
	}
	
	public function getTerm($id) {
		$result = $this->db->query('SELECT * FROM taxonomy_term WHERE id = ?', $id);
		$term = null;
	
		if(is_array($result) && count($result) > 0) {
			$result = $result[0];
			$term = new Term();
			$term->setId($id);
			$term->setDescription($result->description);
			$term->setTitle($result->term);
		}
		
		return $term;
	}
}