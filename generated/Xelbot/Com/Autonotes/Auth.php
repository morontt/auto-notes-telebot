<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT! (protoc-gen-twirp_php 0.13.0)
# source: proto/server.proto

declare(strict_types=1);

namespace Xelbot\Com\Autonotes;

/**
 *
 *
 * Generated from protobuf service <code>xelbot.com.autonotes.Auth</code>
 */
interface Auth
{
    /**
     *
     *
     * Generated from protobuf method <code>xelbot.com.autonotes.Auth/GetToken</code>
     *
     * @throws \Twirp\Error
     */
    public function GetToken(array $ctx, \Xelbot\Com\Autonotes\LoginRequest $req): \Xelbot\Com\Autonotes\LoginResponse;

    /**
     *
     *
     * Generated from protobuf method <code>xelbot.com.autonotes.Auth/RefreshToken</code>
     *
     * @throws \Twirp\Error
     */
    public function RefreshToken(array $ctx, \Xelbot\Com\Autonotes\RefreshTokenRequest $req): \Xelbot\Com\Autonotes\LoginResponse;
}
