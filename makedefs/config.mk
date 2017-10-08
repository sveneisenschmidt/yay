
# Variables
GREP := grep
XARGS := xargs
SED := sed

# macOS compability
ifeq ($(OS),Darwin)
GREP := ggrep
XARGS := gxargs
SED := gsed
endif
REQ_BREW_TAPS = "homebrew/dupes"
REQ_BREW_PACKAGES = "coreutils gnu-sed grep wget findutils"

# Make dependencies
REQ_DEPS = ${GREP} ${XARGS} ${SED}

os-check-requirements:
	# Installed binaries:
	@$(foreach REQ_DEP,$(REQ_DEPS), command -v $(REQ_DEP) >/dev/null 2>&1 \
		&& echo "#	✓ $(REQ_DEP)" || echo "#   × $(REQ_DEP)"; )
	#
	# Howto install needed binaries for OSX/macOS:
	@if [[ "${OS}" -eq "Darwin"  ]] ; then echo "#	$$ brew tap $(REQ_BREW_TAPS)"; fi
	@if [[ "${OS}" -eq "Darwin"  ]] ; then echo "#	$$ brew install $(REQ_BREW_PACKAGES)"; fi