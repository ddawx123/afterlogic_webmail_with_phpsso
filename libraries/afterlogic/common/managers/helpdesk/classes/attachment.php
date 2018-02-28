<?php

/*
 * Copyright 2004-2017, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $IdHelpdeskAttachment
 * @property int $IdHelpdeskPost
 * @property int $IdHelpdeskThread
 * @property int $IdTenant
 * @property int $IdOwner
 * @property int $Created
 * @property int $SizeInBytes
 * @property string $FileName
 * @property string $Content
 * @property string $Hash
 *
 * @package Helpdesk
 * @subpackage Classes
 */
class CHelpdeskAttachment extends api_AContainer
{
	public function __construct()
	{
		parent::__construct(get_class($this));

		$this->SetDefaults(array(
			'IdHelpdeskAttachment'	=> 0,
			'IdHelpdeskPost'		=> 0,
			'IdHelpdeskThread'		=> 0,
			'IdTenant'				=> 0,
			'IdOwner'				=> 0,
			'Created'				=> time(),
			'SizeInBytes'			=> 0,
			'FileName'				=> '',
			'Content'				=> '',
			'Hash'					=> ''
		));
	}

	/**
	 * @param \CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param string $sThreadFolderName
	 */
	public function encodeHash($oHelpdeskUser, $sThreadFolderName)
	{
		$this->Hash = \CApi::EncodeKeyValues(array(
			'FilestorageFile' => true,
			'HelpdeskTenantID' => $oHelpdeskUser->IdTenant,
			'HelpdeskUserID' => $oHelpdeskUser->IdHelpdeskUser,
			'StorageType' => \EFileStorageTypeStr::Corporate,
			'Name' => $this->FileName,
			'Path' => $sThreadFolderName
		));
	}

	/**
	 * @throws CApiValidationException 1106 Errs::Validation_ObjectNotComplete
	 *
	 * @return bool
	 */
	public function validate()
	{
		switch (true)
		{
			case 0 >= $this->IdOwner:
				throw new CApiValidationException(Errs::Validation_ObjectNotComplete, null, array(
					'{{ClassName}}' => 'CHelpdeskPost', '{{ClassField}}' => 'IdOwner'));
		}

		return true;
	}
	
	/**
	 * @return array
	 */
	public function getMap()
	{
		return self::getStaticMap();
	}

	/**
	 * @param \CHelpdeskUser $oUser
	 * @param \CApiHelpdeskManager $oApiHelpdesk
	 * @param \CApiFilestorageManager $oApiFilestorage
	 */
	public function populateContent($oUser, $oApiHelpdesk, $oApiFilestorage)
	{
		$aHash = \CApi::DecodeKeyValues($this->Hash);
		if (isset($aHash['StorageType'], $aHash['Path'], $aHash['Name']) && $oApiHelpdesk && $oApiFilestorage)
		{
			$oHelpdeskUserFromAttachment = null;
			if (isset($aHash['HelpdeskUserID'], $aHash['HelpdeskTenantID']))
			{
				if ($oUser && $aHash['HelpdeskUserID'] === $oUser->IdHelpdeskUser)
				{
					$oHelpdeskUserFromAttachment = $oUser;
				}
				else
				{
					$oHelpdeskUserFromAttachment = $oApiHelpdesk->getUserById(
						$aHash['HelpdeskTenantID'], $aHash['HelpdeskUserID']);
				}
			}

			if ($oHelpdeskUserFromAttachment && $oApiFilestorage->isFileExists(
					$oHelpdeskUserFromAttachment, $aHash['StorageType'], $aHash['Path'], $aHash['Name']
			))
			{
				$mResult = $oApiFilestorage->getFile(
					$oHelpdeskUserFromAttachment, $aHash['StorageType'], $aHash['Path'], $aHash['Name']
				);

				if (is_resource($mResult))
				{
					$this->Content = stream_get_contents($mResult);
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public static function getStaticMap()
	{
		return array(
			'IdHelpdeskAttachment'	=> array('int', 'id_helpdesk_attachment', false, false),
			'IdHelpdeskPost'		=> array('int', 'id_helpdesk_post', true, false),
			'IdHelpdeskThread'		=> array('int', 'id_helpdesk_thread', true, false),
			'IdTenant'				=> array('int', 'id_tenant', true, false),
			'IdOwner'				=> array('int', 'id_owner', true, false),
			'Created'				=> array('datetime', 'created', true, false),
			'SizeInBytes'			=> array('int', 'size_in_bytes'),
			'FileName'				=> array('string', 'file_name'),
			'Content'				=> array('string'),
			'Hash'					=> array('string', 'hash')
		);
	}
}
