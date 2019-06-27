<?php

namespace damidevelopment\apiutils\definitions;


/**
 * ServerErrorResponse definition
 *
 * @Author: Jakub Hrášek
 * @Date:   2018-06-26 12:25:59
 *
 * @SWG\Definition(
 *     definition="ServerErrorResponse",
 *     description="",
 *     type="object",
 *
 *     @SWG\Property(property="code", description="", type="integer", example=500),
 *     @SWG\Property(property="name", description="", type="string", example="Unknown Property"),
 *     @SWG\Property(property="message", description="", type="string", example="Setting unknown property: app\api\v1\resources\EnvResource::macx")
 * )
 */
class ServerErrorResponse
{
}