## 1. BootstrapRunner Constructor Modification

- [x] 1.1 Modify BootstrapRunner constructor to accept optional Loader parameter
- [x] 1.2 Update constructor to use provided Loader or create new instance if null
- [x] 1.3 Add type hint for Loader parameter
- [x] 1.4 Add default null value to maintain backward compatibility

## 2. Getter Method Implementation

- [x] 2.1 Add public get_loader(): Loader method to BootstrapRunner
- [x] 2.2 Implement method to return the Loader instance
- [x] 2.3 Add proper PHPDoc documentation for the method

## 3. Test Updates

- [x] 3.1 Update BootstrapRunnerTest to test constructor with Loader parameter
- [x] 3.2 Update BootstrapRunnerTest to test constructor without parameter (backward compatibility)
- [x] 3.3 Add test to verify get_loader() returns the correct instance
- [x] 3.4 Ensure existing tests still pass with modified constructor

## 4. Documentation and Examples

- [x] 4.1 Update any inline documentation if needed
- [x] 4.2 Verify no breaking changes to existing functionality
- [x] 4.3 Update README.md (documentation spec) to show optional Loader constructor usage example