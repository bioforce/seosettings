<?php
namespace Bioforce\SeoSettings;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use Bioforce\SeoSettings\Models\SeoSetting;
use Bioforce\SeoSettings\Models\SeoTagGroup;

class SeoMeta
{

    /**
     * Cache key.
     *
     * @var string
     */
    protected $cacheKeys = [
        'settings' => 'bf_meta_settigs_',
        'tags' => 'bf_meta_tags'
    ];
    
    /**
     * Get all Routes with filled
     *
     * @return Array
     */
    public function getRoutes()
    {
        $return = [];
        $seoSettings = SeoSetting::get()->keyBy('uri');
        foreach (\Route::getRoutes() as $route) {
            if (!in_array("GET", $route->methods))
            {
                continue;
            }
            $return[] = [
                'route' => $route->uri,
                'methods' => implode(", ", $route->methods),
                'route_name' => $route->getName(),
                'action' => $route->getActionName(),
                'is_filled' => (isset($seoSettings[$route->uri]))?$seoSettings[$route->uri]:NULL,
            ];
        }
        return $return;
    }

    /**
     * Get all models in project
     *
     * @return Array
     */
    public function getModels()
    {
        $path = app_path();
        $return = [];
        $files = \File::allFiles($path);
        foreach ($files as $file) {
            $model = '\\' . ucfirst(str_replace('/', '\\', substr($file, strpos($file, 'app'), -4)));
            $reflectionClass =(new \ReflectionClass($model))->getParentClass();
            if($reflectionClass !== false){
                if($reflectionClass->getName() === "Illuminate\Database\Eloquent\Model" || $reflectionClass->getName() === "Illuminate\Foundation\Auth\User"){
                    $return[$model] = $this->getColumns($model);
                }
            }
        }
        return $return;
    }

    /**
     * Get fillables and appends of Model
     *
     * @return Array
     */
    private function getColumns($model)
    {
        $appends = array_keys((new $model)->attributesToArray(), '', true);
        $fillables = (new $model)->getFillable();
        return array_merge($fillables, $appends);
    }

    /**
     * Get settings by uri
     * 
     * @param string
     *
     * @return SeoSetting
     */
    private function getSeoManagerByUri($uri)
    {
        return SeoSetting::where('uri', $uri)->with('tags')->first();
        return Cache::remember($this->cacheKeys['settings'].$uri, function() use ($uri)  {
            return SeoSetting::where('uri', $uri)->with('tags')->first();
        });
    }

    /**
     * Get list of metatags group with tags
     * 
     * @return SeoTagGroup
     */
    private function getMetaTags(){
        return SeoTagGroup::orderBy('sort')->with(['tags' => function ($query) {
            $query->orderBy('sort', 'desc');
        }])->get();
        return Cache::rememberForever($this->cacheKeys['tags'], function() {
            return SeoTagGroup::orderBy('sort')->with(['tags' => function ($query) {
                $query->orderBy('sort', 'desc');
            }])->get();
        });
    }

    /**
     * Get filled metatags as Array
     *
     * @return Array
     */
    public function getMetaTagsArray()
    {
        $returnArray = [];
        $route = \Route::current();
        if (is_null($route)){
            return $returnArray;
        }

        if (!in_array("GET", $route->methods))
        {
            return $returnArray;
        }

        $seoManager = $this->getSeoManagerByUri($route->uri());
        if(is_null($seoManager)){
            return $returnArray;
        }
                
        $modelItem = $this->getModelItem($route->parameters, $seoManager->model);
        $seoManager = $seoManager->toArray();
        $modelMap = $this->mappingModel($modelItem);

        $tags = $this->getMetaTags();
        //echo "<pre>"; print_r($tags->toArray()); echo "</pre>";
        foreach ($tags as $group)
        {
            $groupArray = [
                'name' => $group->name,
                'tags' => []
            ];
            foreach ($group->tags as $tag)
            {
                $value = $tag->default_value;
                if ($tag->editable)
                {
                    if ($fillTag = $this->getFillTagArray($seoManager['tags'], $tag->id))
                    {
                        $value = str_replace(array_keys($modelMap), array_values($modelMap), $fillTag['pivot']['value']);
                    }
                }
                $groupArray['tags'][] = str_replace('{value}', $value, $tag->template);
            }
            $returnArray[] = $groupArray;
        }
        return $returnArray;
    }

    /**
     * Get filled tag array from array by id
     * 
     * @param array
     * @param string $tagId
     * 
     * @return array
     */
    private function getFillTagArray($seoManagerTagsArray, $tagId)
    {
        return Arr::first($seoManagerTagsArray, function ($value, $key) use ($tagId) {
            return $value['id'] == $tagId;
        });
    }

    /**
     * Get metatags as string to add to Head
     *
     * @return string
     */
    public function getMetaString()
    {
        return $this->getMetaStringFromArray($this->getMetaTagsArray());
    }

    /**
     * Get metastring from metatags Array
     *
     * @param Array
     * @return string
     */
    private function getMetaStringFromArray($metaTagsArray)
    {
        $metaString = '';

        foreach ($metaTagsArray as $group)
        {
            $metaString .= '<!--'.$group['name'].'-->
            ';
            foreach ($group['tags'] as $tag)
            {
                $metaString .= $tag.'
                ';
            }
        }
        return $metaString;
    }

    /**
     * maping model key as {key} and value
     *
     * @param array
     * @return array
     */
    private function mappingModel($array)
    {
        $keys = array_keys($array);

        //Map keys to format function
        $keys = array_map(function ($key){ return "{".$key."}"; }, $keys);

        //Use array_combine to map formatted keys to array values
        return array_combine($keys,$array);
    }

    /**
     * Get Item from route params
     *
     * @return array
     */
    private function getModelItem($parametres, $modelName)
    {
        if (!$modelName)
        {
            return [];
        }
        foreach ($parametres as $key => $parameter)
        {
            if (is_object($parameter))
            {
                return $parameter->toArray();
            }
        }
        $model = (new $modelName);
        foreach ($parametres as $key => $parameter)
        {
            $model = $model->where($key, $parameter);
        }
        return $model->first()->toArray();
    }

}
