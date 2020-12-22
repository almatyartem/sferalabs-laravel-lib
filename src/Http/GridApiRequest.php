<?php

namespace LaravelSferaTemplate\Http;

class GridApiRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'filters' => ['nullable', 'array'],
            'sort' => ['nullable', 'string'],
            'pageSize' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer']
        ];
    }

    /**
     * @return array
     */
    public function getFilters() : array
    {
        return $this->get('filters', []);
    }

    /**
     * @return string|null
     */
    public function getSort() : ?string
    {
        return $this->get('sort');
    }

    /**
     * @param int|null $defaultPageSize
     * @return int|null
     */
    public function getPageSize(int $defaultPageSize = null) : ?int
    {
        return $this->get('pageSize', $defaultPageSize);
    }

    /**
     * @return int|null
     */
    public function getPage() : ?int
    {
        return $this->get('page');
    }
}
