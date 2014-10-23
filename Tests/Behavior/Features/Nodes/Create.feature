Feature:

  Background:
    Given I have the following nodes:
      | Identifier                           | Path        | Node Type              | Properties        | Workspace |
      | ecf40ad1-3119-0a43-d02e-55f8b5aa3c70 | /sites      | unstructured           |                   | live      |
      | fd5ba6e1-4313-b145-1004-dad2f1173a35 | /sites/ease | SimplyAdmire.Ease:Wall | {"title": "Home"} | live      |

  @fixtures
  Scenario:
    Given I send a "POST" request to "ease/nodes/fd5ba6e1-4313-b145-1004-dad2f1173a35"
    Then the response status code should be 409

  @fixtures
  Scenario:
    Given I send a "POST" request to "ease/nodes/fea61b37-4736-c6c6-deab-a9ab0c2c58a0" with the following data::
      | Name              | Value                                |
      | parentNode        | fd5ba6e1-4313-b145-1004-dad2f1173a35 |
      | suggestedNodeName | foo                                  |
      | nodeTypeName      | SimplyAdmire.Ease:Wall               |
      | properties        | {"title": "foo bar"}                 |
      | dimensions        | []                                   |
    Then the response status code should be 409
