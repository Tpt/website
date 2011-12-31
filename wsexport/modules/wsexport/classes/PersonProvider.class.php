<?php
/**
* @author Thomas Pellissier Tanon
* @copyright 2011 Thomas Pellissier Tanon
* @licence http://www.gnu.org/licenses/gpl.html GNU General Public Licence
*/

/**
* provide all the data needed to create a person record
*/
class PersonProvider {
        protected $api = null;

        /**
        * @var $api Api
        */
        public function __construct(Api $api) {
                $this->api = $api;
        }

        /**
        * return all the data
        * @var $title string the title of the author page
        * @return PersonRecord
        */
        public function get($title) {
                $title = str_replace(' ', '_', $title);
                $doc = $this->getDocument($title);
                $parser = new PageParser($doc);
                $person = jClasses::create('PersonRecord');
                $person->title = $title;
                $person->lang = $this->api->lang;
                $person->name = $parser->getMetadata('ws-name');
                $person->description = $parser->getMetadata('ws-description');
                $person->birthDate = $parser->getMetadata('ws-birthdate');
                $person->deathDate = $parser->getMetadata('ws-deathdate');
                $person->key = $parser->getMetadata('ws-key');
                $person->wikipedia = $parser->getMetadata('ws-wikipedia');
                $person->wikiquote = $parser->getMetadata('ws-wikiquote');
                $person->commons = $parser->getMetadata('ws-commons');
                $image = $parser->getMetadata('ws-image');
                if($image != '')
                        $person->imageUrl = $this->getImageUrl($image);
                return $person;
        }

        /**
        * return the content of the page
        * @var $title string the title of the page in Wikisource
        * @return DOMDocument
        */
        protected function getDocument($title) {
                $content = $this->api->getPage($title);
                $document = new DOMDocument('1.0', 'UTF-8');
                $document->loadXML($content);
                return $document;
        }

        protected function getImageUrl($image) {
                $response = $this->api->query(array('titles' => 'File:' . $image, 'prop' => 'imageinfo', 'iiprop' => 'url'));
                $page = end($response['query']['pages']);
                return $page['imageinfo'][0]['url'];
        }
}
