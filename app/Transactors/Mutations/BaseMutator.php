<?php


namespace App\Transactors\Mutations;


use App\Helpers\ArrayHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


/**
 * Base class for all mutators. Performs create update and delete for all models.
 * All models must have public constants CREATE_VALIDATION_RULES and UPDATE_VALIDATION_RULES for this to work as expected
 * If you wish to implement custom logic for update remember to keep the keys optional. Since this calls update to delete
 * an instance. For more information on having optional keys read this class's update method.
 * Class BaseMutator
 * @package App\Transactors\Mutations
 */
class BaseMutator
{

    private $fullyQualifiedModel;
    private $column;

    protected const TIMESTAMP_FORMAT = 'Y-m-d H:i:s';

    /**
     * BaseMutator constructor.
     * @param string $fullyQualifiedModel The name of the model. Must be fully qualified.
     * @param string $column The name of the column in the model to update this instance with.
     */
    public function __construct($fullyQualifiedModel, string $column)
    {
        $this->fullyQualifiedModel = $fullyQualifiedModel;
        $this->column = $column;
    }

    /**
     * Persists an instance
     * @param array $args The data to persist in the model.
     * @return mixed The model instance that got created.
     * @throws \Throwable
     */
    public function create(array $args){
        $validArgs = ArrayHelper::mergePrimaryKeysAndValues($this->fullyQualifiedModel::CREATE_VALIDATION_RULES, $args);
        $validator = Validator::make($validArgs, $this->fullyQualifiedModel::CREATE_VALIDATION_RULES);
        throw_if($validator->fails(),  \ErrorException::class, json_encode($validator->errors()->getMessages()));
        return $this->fullyQualifiedModel::create($validArgs)->fresh();
    }

    /**
     * Updates an instance.
     * @param int $modelId The id of the model to be updated.
     * @param array $args An array of arguments
     * @param $otherColumn If you want to mass update, set other column to some value
     * @return integer Returns 1 if the data is updated, 0 otherwise
     * @throws  \ErrorException If data is invalid
     * @throws \Throwable
     */
    public function update($modelId, array $args, $otherColumn = null){
        $validArgs = ArrayHelper::mergePrimaryKeysAndValues($this->fullyQualifiedModel::UPDATE_VALIDATION_RULES, $args);
        if(array_key_exists('updated_by', $validArgs)){
            $optionalValidationKeys = ArrayHelper::mergePrimaryKeysAndValues($validArgs, $this->fullyQualifiedModel::UPDATE_VALIDATION_RULES);
            $validator = Validator::make($validArgs, $optionalValidationKeys);
            throw_if($validator->fails(),  \ErrorException::class, json_encode($validator->errors()->getMessages()));


            return $this->fullyQualifiedModel::where($otherColumn == null ? $this->column: $otherColumn, '=', $modelId)
                ->whereNull('deleted_at')
                ->firstOrFail()->update($validator->validated());
        }
        throw  new \ErrorException(json_encode(['updated_by' => 'Updated by not given']));

    }

    public function delete($modelId, $deletedBy, $otherColumn = null){
        return $this->update($modelId, ['deleted_at' => Carbon::now()->format(self::TIMESTAMP_FORMAT),
            'updated_by' => $deletedBy], $otherColumn);
    }
}
