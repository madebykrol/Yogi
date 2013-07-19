<?php
namespace smll\cms\framework\content\taxonomy\interfaces;

interface ITaxonomyRepository {
	public function getVocabularyByName($vocabulary);
	public function addVocabulary(IVocabulary $vocabulary);
	public function removeVocabulary(IVocabulary $vocabulary);
	
	public function getAllVocabularies();
	
	public function addTaxonomyTerm(ITaxonomyTerm $term, $vocabularyId);
	public function removeTaxonomyTerm(ITaxonomyTerm $term, $vocabularyId);
	public function getTermsFromVocabulary($vocabularyId);
	
	public function getTerm($id);
	
}