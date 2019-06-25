<?php

namespace damidevelopment\apiutils\definitions;


/**
 * ValidationErrorResponse definition
 *
 * @Author: Jakub Hrášek
 * @Date:   2018-06-21 15:30:38
 *
 * @SWG\Definition(
 *     definition="ValidationErrorResponse",
 *     description="",
 *     type="object",
 *
 *     @SWG\Property(property="code", description="", type="integer", example=422),
 *     @SWG\Property(property="message", description="", type="string", example="Data Validation Failed"),
 *     @SWG\Property(property="errors", description="", type="array", @SWG\Items(ref="#/definitions/ValidationError"))
 * )
 *
 * @SWG\Definition(
 *     definition="ValidationError",
 *     type="object",
 *
 *     @SWG\Property(property="field", description="", type="string", example="mac"),
 *     @SWG\Property(property="messages", description="", type="array", @SWG\Items(type="string", example="Mac cannot be blank."))
 * )
 */
class ValidationErrorResponse
{
}