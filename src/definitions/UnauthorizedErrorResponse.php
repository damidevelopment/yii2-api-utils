<?php

namespace damidevelopment\apiutils\definitions;


/**
 * UnauthorizedErrorResponse definition
 *
 * @Author: Jakub Hrášek
 * @Date:   2018-06-26 12:20:51
 *
 * @SWG\Definition(
 *     definition="UnauthorizedErrorResponse",
 *     description="",
 *     type="object",
 *
 *     @SWG\Property(property="code", description="", type="integer", example=401),
 *     @SWG\Property(property="name", description="", type="string", example="Unauthorized"),
 *     @SWG\Property(property="message", description="", type="string", example="Wrong username or password")
 * )
 */
class UnauthorizedErrorResponse
{
}