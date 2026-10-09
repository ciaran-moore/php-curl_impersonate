/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: f7b6104371bd82847522b6a09322b82ee20ba95e */

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_CURLImpersonateFile___construct, 0, 0, 1)
	ZEND_ARG_TYPE_INFO(0, filename, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, mime_type, IS_STRING, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, posted_filename, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_TENTATIVE_RETURN_TYPE_INFO_EX(arginfo_class_CURLImpersonateFile_getFilename, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_CURLImpersonateFile_getMimeType arginfo_class_CURLImpersonateFile_getFilename

#define arginfo_class_CURLImpersonateFile_getPostFilename arginfo_class_CURLImpersonateFile_getFilename

ZEND_BEGIN_ARG_WITH_TENTATIVE_RETURN_TYPE_INFO_EX(arginfo_class_CURLImpersonateFile_setMimeType, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, mime_type, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_TENTATIVE_RETURN_TYPE_INFO_EX(arginfo_class_CURLImpersonateFile_setPostFilename, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, posted_filename, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_CURLImpersonateStringFile___construct, 0, 0, 2)
	ZEND_ARG_TYPE_INFO(0, data, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, postname, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, mime, IS_STRING, 0, "\"application/octet-stream\"")
ZEND_END_ARG_INFO()

ZEND_METHOD(CURLImpersonateFile, __construct);
ZEND_METHOD(CURLImpersonateFile, getFilename);
ZEND_METHOD(CURLImpersonateFile, getMimeType);
ZEND_METHOD(CURLImpersonateFile, getPostFilename);
ZEND_METHOD(CURLImpersonateFile, setMimeType);
ZEND_METHOD(CURLImpersonateFile, setPostFilename);
ZEND_METHOD(CURLImpersonateStringFile, __construct);

static const zend_function_entry class_CURLImpersonateFile_methods[] = {
	ZEND_ME(CURLImpersonateFile, __construct, arginfo_class_CURLImpersonateFile___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(CURLImpersonateFile, getFilename, arginfo_class_CURLImpersonateFile_getFilename, ZEND_ACC_PUBLIC)
	ZEND_ME(CURLImpersonateFile, getMimeType, arginfo_class_CURLImpersonateFile_getMimeType, ZEND_ACC_PUBLIC)
	ZEND_ME(CURLImpersonateFile, getPostFilename, arginfo_class_CURLImpersonateFile_getPostFilename, ZEND_ACC_PUBLIC)
	ZEND_ME(CURLImpersonateFile, setMimeType, arginfo_class_CURLImpersonateFile_setMimeType, ZEND_ACC_PUBLIC)
	ZEND_ME(CURLImpersonateFile, setPostFilename, arginfo_class_CURLImpersonateFile_setPostFilename, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_CURLImpersonateStringFile_methods[] = {
	ZEND_ME(CURLImpersonateStringFile, __construct, arginfo_class_CURLImpersonateStringFile___construct, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static zend_class_entry *register_class_CURLImpersonateFile(void)
{
	zend_class_entry ce, *class_entry;

	INIT_CLASS_ENTRY(ce, "CURLImpersonateFile", class_CURLImpersonateFile_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_NOT_SERIALIZABLE);

	zval property_name_default_value;
	ZVAL_EMPTY_STRING(&property_name_default_value);
	zend_string *property_name_name = zend_string_init("name", sizeof("name") - 1, 1);
	zend_declare_typed_property(class_entry, property_name_name, &property_name_default_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_STRING));
	zend_string_release(property_name_name);

	zval property_mime_default_value;
	ZVAL_EMPTY_STRING(&property_mime_default_value);
	zend_string *property_mime_name = zend_string_init("mime", sizeof("mime") - 1, 1);
	zend_declare_typed_property(class_entry, property_mime_name, &property_mime_default_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_STRING));
	zend_string_release(property_mime_name);

	zval property_postname_default_value;
	ZVAL_EMPTY_STRING(&property_postname_default_value);
	zend_string *property_postname_name = zend_string_init("postname", sizeof("postname") - 1, 1);
	zend_declare_typed_property(class_entry, property_postname_name, &property_postname_default_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_STRING));
	zend_string_release(property_postname_name);

	return class_entry;
}

static zend_class_entry *register_class_CURLImpersonateStringFile(void)
{
	zend_class_entry ce, *class_entry;

	INIT_CLASS_ENTRY(ce, "CURLImpersonateStringFile", class_CURLImpersonateStringFile_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, 0);

	zval property_data_default_value;
	ZVAL_UNDEF(&property_data_default_value);
	zend_string *property_data_name = zend_string_init("data", sizeof("data") - 1, 1);
	zend_declare_typed_property(class_entry, property_data_name, &property_data_default_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_STRING));
	zend_string_release(property_data_name);

	zval property_postname_default_value;
	ZVAL_UNDEF(&property_postname_default_value);
	zend_string *property_postname_name = zend_string_init("postname", sizeof("postname") - 1, 1);
	zend_declare_typed_property(class_entry, property_postname_name, &property_postname_default_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_STRING));
	zend_string_release(property_postname_name);

	zval property_mime_default_value;
	ZVAL_UNDEF(&property_mime_default_value);
	zend_string *property_mime_name = zend_string_init("mime", sizeof("mime") - 1, 1);
	zend_declare_typed_property(class_entry, property_mime_name, &property_mime_default_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_STRING));
	zend_string_release(property_mime_name);

	return class_entry;
}
