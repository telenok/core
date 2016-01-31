<?php namespace Telenok\Core\Interfaces\Controller;

/**
 * @class Telenok.Core.Interfaces.Controller.IEloquentProcessController
 * Interface for controllers to process models linked to current controllers.
 * 
 * @mixins Telenok.Core.Support.Traits.Language
 */
interface IEloquentProcessController {
    
    /**
     * @method preProcess
     * Called before saving Eloquent model.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Type} $type
     * Eloquent object Type $type.
     * @param {Illuminate.Http.Request} $input
     * Laravel request object.
     * 
     * @return {Telenok.Core.Interfaces.Controller.Controller}
     * @member Telenok.Core.Interfaces.Controller.Controller
     */
    public function preProcess($model, $type, $input);
    
    /**
     * @method postProcess
     * Called after saving Eloquent model.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object} $model
     * Eloquent object.
     * @param {Telenok.Core.Model.Object.Type} $type
     * Eloquent object Type $type.
     * @param {Illuminate.Http.Request} $input
     * Laravel request object.
     * 
     * @return {Telenok.Core.Interfaces.Controller.Controller}
     * @member Telenok.Core.Interfaces.Controller.Controller
     */
    public function postProcess($model, $type, $input);
    
    /**
     * @method validate
     * Called during saving Eloquent model to validate filled data.
     * 
     * @param {Telenok.Core.Interfaces.Eloquent.Object} $model
     * Eloquent object.
     * @param {Illuminate.Http.Request} $input
     * Laravel request object.
     * 
     * @return {Telenok.Core.Interfaces.Controller.Controller}
     * @member Telenok.Core.Interfaces.Controller.Controller
     */
    public function validate($model, $input);
}