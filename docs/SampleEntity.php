<?php

use clagiordano\weblibs\dbabstraction\AbstractEntity;

/**
 * Class SampleEntity
 */
class SampleEntity extends AbstractEntity
{
    protected $allowedFields = ['id', 'title', 'content', 'author', 'comments'];

    /**
     * Set the entry ID
     * @param $id
     */
    public function setId($id)
    {
        if (!filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 999999]])) {
            throw new InvalidArgumentException('The entry ID is invalid.');
        }
        $this->values['id'] = $id;
    }

    /**
     * Set the title of the entry
     * @param $title
     */
    public function setTitle($title)
    {
        if (!is_string($title) || strlen($title) < 2 || strlen($title) > 64) {
            throw new InvalidArgumentException('The title of the entry is invalid.');
        }
        $this->values['title'] = $title;
    }

    /**
     * Set the content of the entry
     */
    public function setContent($content)
    {
        if (!is_string($content) || strlen($content) < 2) {
            throw new InvalidArgumentException('The entry is invalid.');
        }
        $this->values['content'] = $content;
    }

    /**
     * Set the author of the entry
     */
    public function setAuthor($author)
    {
        if (!is_string($author) || strlen($author) < 2 || strlen($author) > 64) {
            throw new InvalidArgumentException('The author of the entry is invalid.');
        }
        $this->values['author'] = $author;
    }
}