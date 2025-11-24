/**
 *  Pages Authentication
 */

'use strict';
const formAuthentication = document.querySelector('#formAuthentication');

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    // Form validation for Add new record
    if (formAuthentication) {
      const fv = FormValidation.formValidation(formAuthentication, {
        fields: {
          name: {
            validators: {
              notEmpty: {
                message: 'Masukan Nama Lengkap Anda'
              },
              // stringLength: {
              //   min: 6,
              //   message: 'Username must be more than 6 characters'
              // }
            }
          },
          email: {
            validators: {
              notEmpty: {
                message: 'Masukan Email Anda'
              },
              emailAddress: {
                message: 'Format Email Tidak Sesuai'
              }
            }
          },
          no_hp: {
            validators: {
              notEmpty: {
                message: 'Masukan Nomor Handphone Anda'
              },
              stringLength: {
                min: 9,
                message: 'Password Minimal 9 Karakter'
              }
              // emailAddress: {
              //   message: 'Format Email Tidak Sesuai'
              // }
            }
          },
          // 'email-username': {
          //   validators: {
          //     notEmpty: {
          //       message: 'Please enter email / username'
          //     },
          //     stringLength: {
          //       min: 6,
          //       message: 'Username must be more than 6 characters'
          //     }
          //   }
          // },
          password: {
            validators: {
              notEmpty: {
                message: 'Password Harus Di isi'
              },
              stringLength: {
                min: 8,
                message: 'Password Minimal 8 Karakter'
              },
              regexp: {
                regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=*!])/,
                message: 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.'
              }
            }
          },
          'k_password': {
            validators: {
              notEmpty: {
                message: 'Password Harus Di isi'
              },
              identical: {
                compare: function () {
                  return formAuthentication.querySelector('[name="password"]').value;
                },
                message: 'Password Tidak Sama'
              },
              stringLength: {
                min: 8,
                message: 'Password Minimal 8 Karakter'
              }
            }
          },
          terms: {
            validators: {
              notEmpty: {
                message: 'Please agree terms & conditions'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.mb-3'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),

          defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', function (e) {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      });
    }

    //  Two Steps Verification
    const numeralMask = document.querySelectorAll('.numeral-mask');

    // Verification masking
    if (numeralMask.length) {
      numeralMask.forEach(e => {
        new Cleave(e, {
          numeral: true
        });
      });
    }
  })();
});
