<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\API
 *
 * @method static \Illuminate\Database\Eloquent\Builder|API newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|API newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|API query()
 */
	class API extends \Eloquent {}
}

namespace App{
/**
 * App\BonusRoyalty
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $from_user_id
 * @property int $amount
 * @property int|null $level_id
 * @property int $status
 * @property string|null $hash
 * @property string $bonus_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRoyalty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRoyalty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRoyalty query()
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRoyalty whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRoyalty whereBonusDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRoyalty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRoyalty whereFromUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRoyalty whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRoyalty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRoyalty whereLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRoyalty whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRoyalty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRoyalty whereUserId($value)
 */
	class BonusRoyalty extends \Eloquent {}
}

namespace App{
/**
 * App\Finance
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string|null $chat_id
 * @property int $is_active
 * @property string|null $active_at
 * @property int $sponsor_id
 * @property string|null $tron
 * @property int $active_credits
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $remember_token
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Finance\USDTbalance[] $USDTbalance
 * @property-read int|null $u_s_d_tbalance_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Finance\Contract[] $contract
 * @property-read int|null $contract_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Finance\Credit[] $credit
 * @property-read int|null $credit_count
 * @property-read \App\LoginSecurity|null $loginSecurity
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Finance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Finance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Finance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Finance whereActiveAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finance whereActiveCredits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finance whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finance whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finance whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finance wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finance whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finance whereSponsorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finance whereTron($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Finance whereUsername($value)
 */
	class Finance extends \Eloquent {}
}

namespace App{
/**
 * App\KbbBonus
 *
 * @property int $id
 * @property int $user_id
 * @property int $affiliate
 * @property int $type
 * @property string $amount
 * @property int $forwarded
 * @property string $hash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|KbbBonus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KbbBonus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KbbBonus query()
 * @method static \Illuminate\Database\Eloquent\Builder|KbbBonus whereAffiliate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbbBonus whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbbBonus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbbBonus whereForwarded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbbBonus whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbbBonus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbbBonus whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbbBonus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KbbBonus whereUserId($value)
 */
	class KbbBonus extends \Eloquent {}
}

namespace App{
/**
 * App\LocalWallet
 *
 * @property int $id
 * @property int $user_id
 * @property string $address
 * @property string $private_key
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|LocalWallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocalWallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocalWallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|LocalWallet whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocalWallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocalWallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocalWallet whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocalWallet wherePrivateKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocalWallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocalWallet whereUserId($value)
 */
	class LocalWallet extends \Eloquent {}
}

namespace App{
/**
 * App\LoginSecurity
 *
 * @property int $id
 * @property int $user_id
 * @property int $google2fa_enable
 * @property string|null $google2fa_secret
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Finance $user
 * @method static \Illuminate\Database\Eloquent\Builder|LoginSecurity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginSecurity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginSecurity query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginSecurity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginSecurity whereGoogle2faEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginSecurity whereGoogle2faSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginSecurity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginSecurity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginSecurity whereUserId($value)
 */
	class LoginSecurity extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Admin
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 */
	class Admin extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Bank
 *
 * @property int $id
 * @property int $user_id
 * @property string $bank
 * @property string $account_no
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Bank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereAccountNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereUserId($value)
 */
	class Bank extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Binaryhistory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Binaryhistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Binaryhistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Binaryhistory query()
 */
	class Binaryhistory extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Bonus
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Bonus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bonus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bonus query()
 */
	class Bonus extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Bonussetting
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Bonussetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bonussetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bonussetting query()
 */
	class Bonussetting extends \Eloquent {}
}

namespace App\Model\Finance{
/**
 * App\Model\Finance\Contract
 *
 * @property int $id
 * @property int $user_id
 * @property int $strategy
 * @property int $status 0 = Inactive, 1 = Active, 2 = Ended
 * @property string $principal
 * @property string $compounded
 * @property int $grade 0 = Processing, 1 = C, 2 = B, 3 = A, 4 = S
 * @property int $collateralized 0 = No, 1 = Yes
 * @property string|null $expired_at
 * @property string|null $next_yield_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Finance $user
 * @method static \Illuminate\Database\Eloquent\Builder|Contract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereCollateralized($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereCompounded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereNextYieldAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract wherePrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereStrategy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereUserId($value)
 */
	class Contract extends \Eloquent {}
}

namespace App\Model\Finance{
/**
 * App\Model\Finance\Credit
 *
 * @property int $id
 * @property int $user_id
 * @property string $amount
 * @property int $type 0 = debit, 1 = credit
 * @property int $source 1 = ref fee, 2 = withdraw, 3 = transfer, 4 = convert
 * @property int $source_id user_id for fee and transfer, 0 for withdraw and convert
 * @property string $tx_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Finance $user
 * @method static \Illuminate\Database\Eloquent\Builder|Credit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Credit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Credit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereTxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Credit whereUserId($value)
 */
	class Credit extends \Eloquent {}
}

namespace App\Model\Finance{
/**
 * App\Model\Finance\PerformanceIndex
 *
 * @property int $id
 * @property int $strategy
 * @property string $index
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceIndex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceIndex newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceIndex query()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceIndex whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceIndex whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceIndex whereIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceIndex whereStrategy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceIndex whereUpdatedAt($value)
 */
	class PerformanceIndex extends \Eloquent {}
}

namespace App\Model\Finance{
/**
 * App\Model\Finance\USDTbalance
 *
 * @property int $id
 * @property int $user_id
 * @property string $amount
 * @property int $type 0 = debit, 1 = credit
 * @property int $status 0 = pending, 1 = final
 * @property string|null $hash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Finance $user
 * @method static \Illuminate\Database\Eloquent\Builder|USDTbalance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|USDTbalance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|USDTbalance query()
 * @method static \Illuminate\Database\Eloquent\Builder|USDTbalance whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|USDTbalance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|USDTbalance whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|USDTbalance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|USDTbalance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|USDTbalance whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|USDTbalance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|USDTbalance whereUserId($value)
 */
	class USDTbalance extends \Eloquent {}
}

namespace App\Model\Finance{
/**
 * App\Model\Finance\_Yield
 *
 * @property int $id
 * @property int $contract_id
 * @property string $amount
 * @property int $type 0 = debit, 1 = credit
 * @property int|null $action 0 = withdraw, 1 = compound
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Model\Finance\Contract $contract
 * @method static \Illuminate\Database\Eloquent\Builder|_Yield newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|_Yield newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|_Yield query()
 * @method static \Illuminate\Database\Eloquent\Builder|_Yield whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|_Yield whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|_Yield whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|_Yield whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|_Yield whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|_Yield whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|_Yield whereUpdatedAt($value)
 */
	class _Yield extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Historyindex
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Historyindex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Historyindex newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Historyindex query()
 */
	class Historyindex extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Masterpin
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Masterpin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Masterpin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Masterpin query()
 */
	class Masterpin extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Member
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member query()
 */
	class Member extends \Eloquent {}
}

namespace App\Model\Member{
/**
 * App\Model\Member\Bank
 *
 * @property int $id
 * @property int $user_id
 * @property string $bank
 * @property string $account_no
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Bank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereAccountNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereUserId($value)
 */
	class Bank extends \Eloquent {}
}

namespace App\Model\Member{
/**
 * App\Model\Member\Category
 *
 * @property int $id
 * @property string $name
 * @property int $royalty
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Model\Member\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereRoyalty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Model\Member{
/**
 * App\Model\Member\DigitalSale
 *
 * @property int $id
 * @property int $user_id
 * @property int $vendor_id
 * @property string|null $ppob_code
 * @property int $type 1 => Pulsa, 2 => Paket Data, 3=> PLN, 4 => BPJS, 5 => Pasca, 6 => Telkom, 7 => Tagihan, 8 => Lain ke-1, 9=> Lain  ke-2
 * @property string $ppob_price
 * @property string $ppob_date
 * @property int $status 0 = belum, 1 = member transfer 2 = tuntas dr vendor, 3 = batal
 * @property string|null $reason
 * @property int $buy_metode 1 = COD, 2 = Transfer Bank, 3 = Tron
 * @property string|null $tron
 * @property string|null $tron_transfer
 * @property string|null $bank_name
 * @property string|null $account_no
 * @property string|null $account_name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $tuntas_at
 * @property string|null $buyer_code
 * @property string|null $product_name
 * @property string|null $message
 * @property string|null $harga_modal
 * @property string|null $confirm_at
 * @property string|null $return_buy
 * @property int $vendor_approve 1 = Pending, 2 => Tuntas, 3 => gagal
 * @property string|null $vendor_cek
 * @property-read \App\User $buyer
 * @property-read \App\User $seller
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale query()
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereAccountNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereBuyMetode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereBuyerCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereConfirmAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereHargaModal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale wherePpobCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale wherePpobDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale wherePpobPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereReturnBuy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereTron($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereTronTransfer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereTuntasAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereVendorApprove($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereVendorCek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DigitalSale whereVendorId($value)
 */
	class DigitalSale extends \Eloquent {}
}

namespace App\Model\Member{
/**
 * App\Model\Member\EidrBalance
 *
 * @property int $id
 * @property int $user_id
 * @property string $amount
 * @property int $type
 * @property int $source 0 = outflow, 1 = LMBdiv, 2 = Sponsor, 3 = Binary, 4 = IDR deposit, 5 = eIDR deposit, 6 = Sales
 * @property string|null $tx_id
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EidrBalance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EidrBalance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EidrBalance query()
 * @method static \Illuminate\Database\Eloquent\Builder|EidrBalance whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EidrBalance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EidrBalance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EidrBalance whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EidrBalance whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EidrBalance whereTxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EidrBalance whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EidrBalance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EidrBalance whereUserId($value)
 */
	class EidrBalance extends \Eloquent {}
}

namespace App\Model\Member{
/**
 * App\Model\Member\LMBreward
 *
 * @property int $id
 * @property int $user_id
 * @property string $amount
 * @property string|null $sales
 * @property int $type 0 = claimed, 1 = credited
 * @property string|null $hash
 * @property string|null $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_store 0 = Shopping Reward, 1 = Selling Reward
 * @method static \Illuminate\Database\Eloquent\Builder|LMBreward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LMBreward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LMBreward query()
 * @method static \Illuminate\Database\Eloquent\Builder|LMBreward whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LMBreward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LMBreward whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LMBreward whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LMBreward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LMBreward whereIsStore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LMBreward whereSales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LMBreward whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LMBreward whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LMBreward whereUserId($value)
 */
	class LMBreward extends \Eloquent {}
}

namespace App\Model\Member{
/**
 * App\Model\Member\MasterSales
 *
 * @property int $id
 * @property int $user_id
 * @property int $stockist_id
 * @property string $invoice
 * @property string $total_price
 * @property string $sale_date
 * @property int $status 0 = belum, 1 = konfirmasi member, 2 = konfirmasi admin, 3 = batal
 * @property string|null $reason
 * @property int $buy_metode 1 = COD, 2 = Transfer Bank, 3 = Tron
 * @property string|null $tron
 * @property string|null $tron_transfer
 * @property string|null $bank_name
 * @property string|null $account_no
 * @property string|null $account_name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\User $buyer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Member\Sales[] $sales
 * @property-read int|null $sales_count
 * @property-read \App\User $seller
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales query()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereAccountNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereBuyMetode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereSaleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereStockistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereTron($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereTronTransfer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterSales whereUserId($value)
 */
	class MasterSales extends \Eloquent {}
}

namespace App\Model\Member{
/**
 * App\Model\Member\Product
 *
 * @property int $id
 * @property int $type 1 = Stockist, 2 = Vendor
 * @property int $seller_id
 * @property string $name
 * @property string $size
 * @property string $price
 * @property string|null $desc
 * @property int $qty
 * @property int $category_id
 * @property string $image
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $seller
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 */
	class Product extends \Eloquent {}
}

namespace App\Model\Member{
/**
 * App\Model\Member\Region
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region query()
 */
	class Region extends \Eloquent {}
}

namespace App\Model\Member{
/**
 * App\Model\Member\Sales
 *
 * @property int $id
 * @property int $user_id
 * @property int $stockist_id
 * @property int $purchase_id
 * @property string $invoice
 * @property string $amount
 * @property string $sale_price
 * @property string $sale_date
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $master_sales_id
 * @property-read \App\Model\Member\MasterSales|null $masterSales
 * @property-read \App\Model\Member\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|Sales newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sales newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sales query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereMasterSalesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales wherePurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereSaleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereStockistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereUserId($value)
 */
	class Sales extends \Eloquent {}
}

namespace App\Model\Member{
/**
 * App\Model\Member\SellerProfile
 *
 * @property int $id
 * @property int $seller_id
 * @property string $shop_name
 * @property string $motto
 * @property string $no_hp
 * @property string|null $tg_user
 * @property string $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $seller
 * @method static \Illuminate\Database\Eloquent\Builder|SellerProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|SellerProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerProfile whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerProfile whereMotto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerProfile whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerProfile whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerProfile whereShopName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerProfile whereTgUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellerProfile whereUpdatedAt($value)
 */
	class SellerProfile extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Memberpackage
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Memberpackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Memberpackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Memberpackage query()
 */
	class Memberpackage extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Membership
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership query()
 */
	class Membership extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Package
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Package newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Package newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Package query()
 */
	class Package extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Pengiriman
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Pengiriman newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengiriman newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengiriman query()
 */
	class Pengiriman extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Pin
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Pin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pin query()
 */
	class Pin extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Pinsetting
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Pinsetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pinsetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pinsetting query()
 */
	class Pinsetting extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Repeatorder
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Repeatorder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Repeatorder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Repeatorder query()
 */
	class Repeatorder extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Sales
 *
 * @property int $id
 * @property int $user_id
 * @property int $stockist_id
 * @property int $purchase_id
 * @property string $invoice
 * @property string $amount
 * @property string $sale_price
 * @property string $sale_date
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $master_sales_id
 * @method static \Illuminate\Database\Eloquent\Builder|Sales newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sales newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sales query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereMasterSalesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales wherePurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereSaleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereStockistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sales whereUserId($value)
 */
	class Sales extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Transaction
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 */
	class Transaction extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Transferwd
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Transferwd newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transferwd newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transferwd query()
 */
	class Transferwd extends \Eloquent {}
}

namespace App\Model{
/**
 * App\Model\Validation
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Validation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Validation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Validation query()
 */
	class Validation extends \Eloquent {}
}

namespace App{
/**
 * App\Order
 *
 * @property int $id
 * @property int $user_id
 * @property int $stockist_id
 * @property string $invoice
 * @property string $total_price
 * @property string $sale_date
 * @property int $status 0 = belum, 1 = konfirmasi member, 2 = konfirmasi admin, 3 = batal
 * @property string|null $reason
 * @property int $buy_metode 1 = COD, 2 = Transfer Bank, 3 = Tron
 * @property string|null $tron
 * @property string|null $tron_transfer
 * @property string|null $bank_name
 * @property string|null $account_no
 * @property string|null $account_name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAccountNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBuyMetode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSaleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStockistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTron($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTronTransfer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 */
	class Order extends \Eloquent {}
}

namespace App{
/**
 * App\OrderDetail
 *
 * @property int $id
 * @property int $user_id
 * @property int $stockist_id
 * @property int $purchase_id
 * @property string $invoice
 * @property string $amount
 * @property string $sale_price
 * @property string $sale_date
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $master_sales_id
 * @property-read \App\Order|null $order
 * @property-read \App\Model\Member\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereMasterSalesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail wherePurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereSaleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereStockistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereUserId($value)
 */
	class OrderDetail extends \Eloquent {}
}

namespace App{
/**
 * App\OrderDetailVendor
 *
 * @property int $id
 * @property int $user_id
 * @property int $vendor_id
 * @property int $purchase_id
 * @property string $invoice
 * @property string $amount
 * @property string $sale_price
 * @property string $sale_date
 * @property string|null $reason
 * @property int|null $vmaster_sales_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property string|null $deleted_at
 * @property-read \App\Order|null $order
 * @property-read \App\Model\Member\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor whereInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor wherePurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor whereSaleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor whereVendorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetailVendor whereVmasterSalesId($value)
 */
	class OrderDetailVendor extends \Eloquent {}
}

namespace App{
/**
 * App\OrderVendor
 *
 * @property int $id
 * @property int $user_id
 * @property int $vendor_id
 * @property string $invoice
 * @property string $total_price
 * @property string $sale_date
 * @property int $status 0 = belum, 1 = konfirmasi member, 2 = konfirmasi admin, 3 = batal
 * @property string|null $reason
 * @property int $buy_metode 1 = COD, 2 = Transfer Bank, 3 = Tron
 * @property string|null $tron
 * @property string|null $tron_transfer
 * @property string|null $bank_name
 * @property string|null $account_no
 * @property string|null $account_name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\User $buyer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrderDetail[] $orderDetails
 * @property-read int|null $order_details_count
 * @property-read \App\User $seller
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor newQuery()
 * @method static \Illuminate\Database\Query\Builder|OrderVendor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereAccountNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereBuyMetode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereSaleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereTron($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereTronTransfer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderVendor whereVendorId($value)
 * @method static \Illuminate\Database\Query\Builder|OrderVendor withTrashed()
 * @method static \Illuminate\Database\Query\Builder|OrderVendor withoutTrashed()
 */
	class OrderVendor extends \Eloquent {}
}

namespace App{
/**
 * App\Ppob
 *
 * @property int $id
 * @property int $user_id
 * @property int $vendor_id
 * @property string|null $ppob_code
 * @property int $type 1 => Pulsa, 2 => Paket Data, 3=> PLN, 4 => BPJS, 5 => Pasca, 6 => Telkom, 7 => Tagihan, 8 => Lain ke-1, 9=> Lain  ke-2
 * @property string $ppob_price
 * @property string $ppob_date
 * @property int $status 0 = belum, 1 = member transfer 2 = tuntas dr vendor, 3 = batal
 * @property string|null $reason
 * @property int $buy_metode 1 = COD, 2 = Transfer Bank, 3 = Tron
 * @property string|null $tron
 * @property string|null $tron_transfer
 * @property string|null $bank_name
 * @property string|null $account_no
 * @property string|null $account_name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $tuntas_at
 * @property string|null $buyer_code
 * @property string|null $product_name
 * @property string|null $message
 * @property string|null $harga_modal
 * @property string|null $confirm_at
 * @property string|null $return_buy
 * @property int $vendor_approve 1 = Pending, 2 => Tuntas, 3 => gagal
 * @property string|null $vendor_cek
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereAccountNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereBuyMetode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereBuyerCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereConfirmAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereHargaModal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob wherePpobCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob wherePpobDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob wherePpobPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereReturnBuy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereTron($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereTronTransfer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereTuntasAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereVendorApprove($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereVendorCek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ppob whereVendorId($value)
 */
	class Ppob extends \Eloquent {}
}

namespace App{
/**
 * App\ProductImages
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImages query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImages whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImages whereUpdatedAt($value)
 */
	class ProductImages extends \Eloquent {}
}

namespace App{
/**
 * App\TronModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TronModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TronModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TronModel query()
 */
	class TronModel extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $hp
 * @property string $password
 * @property string|null $2fa
 * @property string|null $username
 * @property int $is_login 0 = tidak aktif, 1 = aktif
 * @property int $is_active 0 = tidak aktif, 1 = aktif
 * @property int $user_type 1 = super admin, 2 = master admin, 3 = admin, 10 = member
 * @property int $is_store
 * @property int $id_type Berhubungan dgn type manager. 1=> member biasa, 11 => TL, 12 => Asmen, 13 => M, 14 => SM, 15 => EM, 16 => SEM, 17 => GM
 * @property int|null $package_id jenis paket yg dibeli
 * @property int $member_type Berhubungan dgn order paket diawal setelah diaktifasi. 0 => belum pernah aktifasi pin, 1 =>  reseller, 2 => Agen, 3 => Stockist 4 => Master Stockist
 * @property int $member_status Berhubungan dgn pembelian total pin. 0 => belum pernah beli pin, 1 =>  member biasa (pebelian pin 1-99)  2=> Director Stockist (Pebelian pin >= 100 pin)
 * @property int|null $sponsor_id
 * @property int $total_sponsor
 * @property int|null $upline_id
 * @property int|null $kiri_id
 * @property int|null $kanan_id
 * @property string|null $upline_detail
 * @property int $is_referal_link 0 = bukan, 1 = iya
 * @property string|null $full_name buat di account_name bank
 * @property string|null $alamat
 * @property string|null $provinsi
 * @property string|null $kota
 * @property int|null $gender 1 = laki-laki, 2 = perempuan
 * @property int $is_profile 0 = belum, 1 = sudah
 * @property string|null $active_at
 * @property string|null $package_id_at
 * @property string|null $member_status_at
 * @property string|null $profile_created_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $expired_at
 * @property string|null $remember_token
 * @property string|null $placement_at
 * @property int $is_tron 0 = belum, 1 = sudah
 * @property string|null $tron
 * @property int|null $bank_id
 * @property string|null $tron_at
 * @property string|null $kecamatan
 * @property string|null $kelurahan
 * @property int $is_stockist
 * @property string|null $stockist_at
 * @property string|null $kode_daerah
 * @property int $pin_activate
 * @property string|null $pin_activate_at
 * @property string|null $permission
 * @property int $is_vendor
 * @property string|null $vendor_at
 * @property int $affiliate
 * @property string|null $chat_id
 * @property int|null $invited_by
 * @property-read \App\Model\Member\Bank|null $bank
 * @property-read \App\LocalWallet|null $localWallet
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Member\Product[] $products
 * @property-read int|null $products_count
 * @property-read \App\Model\Member\SellerProfile|null $sellerProfile
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User where2fa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActiveAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAffiliate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIdType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereInvitedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsReferalLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsStockist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsStore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsTron($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsVendor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKelurahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKiriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKodeDaerah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMemberStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMemberStatusAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMemberType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePackageIdAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePinActivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePinActivateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePlacementAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfileCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSponsorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStockistAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTotalSponsor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTron($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTronAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUplineDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUplineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVendorAt($value)
 */
	class User extends \Eloquent {}
}

