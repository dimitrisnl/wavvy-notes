<?php

namespace App\Transformers;

use App\Note;
use League\Fractal\TransformerAbstract;

class NoteTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'category',
    ];

    /**
     * List of default includes.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'category',
    ];

    /**
     * Transform a note.
     *
     * @param  Note $note
     *
     * @return array
     */
    public function transform(Note $note)
    {
        return [
            'id'         => $note->id,
            'name'       => $note->name,
            'body'       => $note->body,
            'created_at' => $note->created_at,
            'updated_at' => $note->updated_at,
        ];
    }

    /**
     * Include category.
     *
     * @param  Note $note
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeCategory(Note $note)
    {
        return $this->item($note->category, new CategoryTransformer);
    }
}
