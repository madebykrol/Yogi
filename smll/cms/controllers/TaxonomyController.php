<?php
namespace smll\cms\controllers;

use smll\cms\framework\content\taxonomy\Term;

use smll\cms\models\TermsListModel;

use smll\cms\models\TaxonomyTermModel;

use smll\framework\utils\HashMap;

use smll\cms\models\VocabularyModel;

use smll\cms\framework\content\taxonomy\Vocabulary;

use smll\framework\mvc\Controller;

use smll\cms\framework\content\taxonomy\interfaces\ITaxonomyTerm;

use smll\cms\framework\content\taxonomy\interfaces\IVocabulary;

use smll\cms\framework\content\taxonomy\interfaces\ITaxonomyRepository;

/**
 *
 * @author Kristoffer "mbk" Olsson
 *
 * [Authorize]
 */
class TaxonomyController extends Controller
{

    protected $taxonomyRepository = null;


    public function __construct(ITaxonomyRepository $repo)
    {
        $this->taxonomyRepository = $repo;
    }

    public function index()
    {
        return $this->view($this->taxonomyRepository->getAllVocabularies());
    }

    public function new_vocabulary()
    {
        $vocabulary = new VocabularyModel();

        return $this->view($vocabulary);
    }

    public function post_new_vocabulary(VocabularyModel $vocabulary)
    {

        if ($this->modelState->isValid()) {
            $vocab = new Vocabulary();
            $vocab->setName($vocabulary->name);
            $vocab->setDescription($vocabulary->description);
             
            $id = $this->taxonomyRepository->addVocabulary($vocab);
            if (is_numeric($id)) {
                return $this->redirectToAction('vocabulary-list-terms', 
                        null, new HashMap(array('vocabulary' => $id)));
            }
        }

        return $this->view($vocabulary);
    }

    public function edit_vocabulary($vocabulary)
    {

    }

    public function post_edit_vocabulary(IVocabulary $vocabulary)
    {

    }

    public function remove_vocabulary($vocabulary)
    {

    }

    public function vocabulary_list_terms($vocabulary)
    {
        $terms = new TermsListModel();
        $terms->terms = $this->taxonomyRepository->getTermsFromVocabulary($vocabulary);
        $terms->vocabularyId = $vocabulary;
        return $this->view($terms);
    }

    public function add_term($vocabulary, $returnUrl)
    {
        $termModel = new TaxonomyTermModel();
        $termModel->vocabulary = $vocabulary;
        $termModel->returnUrl = $returnUrl;
        return $this->view($termModel);
    }

    public function post_add_term(TaxonomyTermModel $term, $returnUrl)
    {

        if ($this->modelState->isValid()) {
            $newTerm = new Term();
            $newTerm->setDescription($term->description);
            $newTerm->setTitle($term->term);
            $newTerm->setParent($term->parent);
            $newTerm->setShort($term->short);
             
            $this->taxonomyRepository->addTaxonomyTerm($newTerm, $term->vocabulary);
             
            return $this->redirectToUri(urldecode($returnUrl));
        }
        return $this->redirectToUri(urldecode($returnUrl));
    }

    public function edit_term($termid)
    {

    }

    public function post_edit_term(ITaxonomyTerm $term)
    {

    }


}