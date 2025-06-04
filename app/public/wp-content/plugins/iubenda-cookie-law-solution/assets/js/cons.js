/**
 * Main iubenda frontend cons functions
 *
 * @package  Iubenda
 */

// Library initialization.
var _iub               = _iub || {};
_iub.cons_instructions = _iub.cons_instructions || [];
_iub.cons_instructions.push(
	["init", {
		api_key: iubConsParams.api_key,
		log_level: iubConsParams.log_level,
		logger: iubConsParams.logger,
		sendFromLocalStorageAtLoad: iubConsParams.send_from_local_storage_at_load
	}, function () {
		// console.log( "init callBack" );.
	}
	]
);
