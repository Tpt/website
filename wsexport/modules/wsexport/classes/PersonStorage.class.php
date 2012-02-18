<?php
/**
* @package   wsexport
* @subpackage wsexport
* @author Thomas Pellissier Tanon
* @copyright 2011 Thomas Pellissier Tanon
* @licence http://www.gnu.org/licenses/gpl.html GNU General Public Licence
*/

/**
 * Abstraction of the person stockage
 * @package   wsexport
 * @subpackage wsexport
 */
class personStorage {
        public static $SEARCH_KEYS = array('name', 'birthdate', 'deathdate');
        protected $factory = null;

        public function __construct() {
                $this->factory = jDao::get('person');
        }

        /**
         * get metadatas of a person
         * @param $lang string the language code
         * @param $title string the title of the person page
         * @return PersonRecord
         * @todo categories
         */
        public function get($lang, $title) {
      	        $personDao = $this->factory->get(array($lang, $title));
		if($personDao == null)
        		throw new HttpException('Not Found', 404);
                return $this->getPerson($personDao);
        }

        /**
         * get metadatas of people
         * @param $params array|string the search params like 'property' => 'value'
         * @param $order string the param to sort with
         * @param $wayAsc bool if the search sort ascendently.
         * @param $itemPerPage integer number of result to return
         * @param $offset integer index of the current result
         * @return array 0 => number of results, 1 => array|personRecord
         * @todo categories
         */
        public function gets($params, $order = '', $wayAsc = true, $itemPerPage = 20, $offset = 0) {
                $people = array();
                $conditions = jDao::createConditions();
                foreach($params as $id => $value) {
                        $conditions->addCondition($id, 'LIKE', $value);
                }
                if($order != '') {
                        if($wayAsc)
                                $conditions->addItemOrder($order, 'ASC');
                        else
                                $conditions->addItemOrder($order, 'DESC');

                }
                $count = $this->factory->countBy($conditions);
                $liste = $this->factory->findBy($conditions, $offset, $itemPerPage);
                foreach($liste as $personDao) {
      	                $people[] = $this->getPerson($personDao);
                }
                return array($count, $people);
        }


        /**
         * get the title of a person page select by random
         * @param $lang string the language code
         * @return string
         */
        public function getRandomTitle($lang) {
                $personDao = $this->factory->random($lang);
                return $personDao->title;
        }

        /**
         * add metadata of the person to the database
         * @param $lang the lang of the wikisource
         * @param $title string the title of the page
         * @param $lastrevid integer the last revision id of the main page in Wikisource
         */
        public function create($lang, $title, $lastrevid = 0) {
                $person = $this->getApi($lang, $title);
                if($person->name == '')
                        return;
                $personDao = $this->getpersonRecord($person);
                $personDao->lastrevid = $lastrevid;
                $this->factory->insert($personDao);
        }

        /**
         * update metadata of the person to the database
         * @param $lang the lang of the wikisource
         * @param $title string the title of the person
         * @param $lastrevid integer the last revision id of the main page in Wikisource
         */
        public function update($lang, $title, $lastrevid = 0) {
                $person = $this->getApi($lang, $title);
                $personDao = $this->factory->get(array($lang, $title));
                $personDao = $this->updatepersonRecord($personDao, $person);
                $personDao->lastrevid = $lastrevid;
                $this->factory->update($personDao);
        }

        /**
         * delete metadata of the person to the database
         * @param $lang the lang of the wikisource
         * @param $title string the title of the person
         */
        public function delete($lang, $title) {
                $this->factory->delete(array($lang, $title));
        }

        /**
         * update database from book list
         * @param $lang string the lang of the wikisource
         */
        public function refresh($lang) {
                $conditions = jDao::createConditions();
                $conditions->addCondition('lang', '=', $lang);
                $books = jDao::get('book')->findBy($conditions);
                foreach($books as $book) {
                        $this->refreshPerson($lang, $book->author);
                        $this->refreshPerson($lang, $book->translator);
                        $this->refreshPerson($lang, $book->illustrator);
                }
        }

        /**
         * update a person from Wikisource
         * @param $lang the lang of the wikisource
         * @param $title string the title of the person
         */
        protected function refreshPerson($lang, $title) {
                if($title == '')
                        return;
                $title = str_replace(array(' ', '/'), array('_', '%2F'), $title);
      	        $personDao = $this->factory->get(array($lang, $title));
		if($personDao == null) {
        	        try {
        		        $this->create($lang, $title, 0); //TODO : evision id for sync
                        } catch(HttpException $e) {}
                } else if($personDao->lastrevid != 0) {
                        $this->update($lang, $title, 0);
                }
        }

        /**
         * get metadatas of a person from Wikisource
         * @param $lang the lang of the wikisource
         * @param $title string the title of the person
         * @return person
         */
        protected function getApi($lang, $title) {
                $api = new Api($lang);
                jClasses::inc('PersonProvider');
                $provider = new PersonProvider($api);
                return $provider->get($title);
        }

        /**
         * get person objet from person record
         * @param $personDao jDaoRecordBase
         * @return personRecord
         * @todo categories
         */
        protected function getPerson(jDaoRecordBase $personDao) {
                $person = jClasses::create('PersonRecord');
                $person->title = urlencode($personDao->title);
                $person->lang = $personDao->lang;
                $person->name = $personDao->name;
                $person->description = $personDao->description;
                $person->birthDate = $personDao->birthDate;
                $person->deathDate = $personDao->deathDate;
                $person->imageUrl = $personDao->imageUrl;
                $person->key = $personDao->key;
                $person->wikipedia = $personDao->wikipedia;
                $person->wikiquote = $personDao->wikiquote;
                $person->commons = $personDao->commons;
                return $person;
        }

        /**
         * get person record from person object
         * @param $person person
         * @return jDaoRecordBase
         */
        protected function getPersonRecord(PersonRecord $person) {
                $personDao = jDao::createRecord('person');
                return $this->updatepersonRecord($personDao, $person);
        }

        /**
         * udate a person record from data of a person object
         * @param $personDao jDaoRecordBase
         * @param $person person
         * @return jDaoRecordBase
         */
        protected function updatePersonRecord(jDaoRecordBase $personDao, PersonRecord $person) {
                $personDao->title = $person->title;
                $personDao->lang = $person->lang;
                $personDao->name = $person->name;
                $personDao->description = $person->description;
                $personDao->birthDate = $person->birthDate;
                $personDao->deathDate = $person->deathDate;
                $personDao->key = $person->key;
                $personDao->wikipedia = $person->wikipedia;
                $personDao->wikiquote = $person->wikiquote;
                $personDao->commons = $person->commons;
                $personDao->imageUrl = $person->imageUrl;
                return $personDao;
        }
}

