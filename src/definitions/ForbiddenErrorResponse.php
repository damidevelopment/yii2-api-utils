<?php

namespace damidevelopment\apiutils\definitions;


/**
 * ForbiddenErrorResponse definition
 *
 * @Author: Jakub Hrášek
 * @Date:   2018-06-26 12:24:40
 *
 * @SWG\Definition(
 *     definition="ForbiddenErrorResponse",
 *     description="",
 *     type="object",
 *
 *     @SWG\Property(property="code", description="", type="integer", example=403),
 *     @SWG\Property(property="name", description="", type="string", example="Forbidden"),
 *     @SWG\Property(property="message", description="", type="string", example="You are not allowed to see this content")
 * )
 */
class ForbiddenErrorResponse
{
}