<?php

namespace damidevelopment\apiutils\definitions;


/**
 * NotAllowedErrorResponse definition
 *
 * @Author: Jakub Hrášek
 * @Date:   2018-06-26 11:29:36
 *
 * @SWG\Definition(
 *     definition="NotAllowedErrorResponse",
 *     description="",
 *     type="object",
 *
 *     @SWG\Property(property="code", description="", type="integer", example=405),
 *     @SWG\Property(property="name", description="", type="string", example="Method Not Allowed"),
 *     @SWG\Property(property="message", description="", type="string", example="Method Not Allowed. This URL can only handle the following request methods: POST.")
 * )
 */
class NotAllowedErrorResponse
{
}