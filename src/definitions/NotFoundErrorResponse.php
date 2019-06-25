<?php

namespace damidevelopment\apiutils\definitions;


/**
 * NotFoundErrorResponse definition
 *
 * @Author: Jakub Hrášek
 * @Date:   2018-06-26 12:30:49
 *
 * @SWG\Definition(
 *     definition="NotFoundErrorResponse",
 *     description="",
 *     type="object",
 *
 *     @SWG\Property(property="code", description="", type="integer", example=404),
 *     @SWG\Property(property="name", description="", type="string", example="Not Found"),
 *     @SWG\Property(property="message", description="", type="string", example="Item not found")
 * )
 */
class NotFoundErrorResponse
{
}