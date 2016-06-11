<?php

namespace Telenok\Core\Contract\Eloquent;

/**
 * @class Telenok.Core.Contract.Eloquent.EloquentProcessController
 * Interface for controllers to process models linked to current controllers.
 */
interface EloquentProcessController {
    
    /**
     * @method preProcess
     * Called before saving Eloquent model.
     * 
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Type} $type
     * Eloquent object Type $type.
     * @param {Illuminate.Http.Request} $input
     * Laravel request object.
     * 
     * @return {Telenok.Core.Abstraction.Controller.Controller}
     * @member Telenok.Core.Contract.Controller.Controller
     */
    public function preProcess($model, $type, $input);
    
    /**
     * @method postProcess
     * Called after saving Eloquent model.
     * 
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Type} $type
     * Eloquent object Type $type.
     * @param {Illuminate.Http.Request} $input
     * Laravel request object.
     * 
     * @return {Telenok.Core.Abstraction.Controller.Controller}
     * @member Telenok.Core.Contract.Controller.Controller
     */
    public function postProcess($model, $type, $input);
    
    /**
     * @method validate
     * Called during saving Eloquent model to validate filled data.
     * 
     * @param {Telenok.Core.Abstraction.Eloquent.Object.Model} $model
     * Eloquent object.
     * @param {Illuminate.Http.Request} $input
     * Laravel request object.
     * 
     * @return {Telenok.Core.Abstraction.Controller.Controller}
     * @member Telenok.Core.Contract.Controller.Controller
     */
    public function validate($model, $input);
}