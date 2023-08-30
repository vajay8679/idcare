/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React,{Component} from 'react';
import {
  SafeAreaView,
  StyleSheet,
  ScrollView,
  View,
  TextInput,
  StatusBar,
  TouchableOpacity,
  Image
 
} from 'react-native';
import { Container, Header, Content, Footer, FooterTab, Button, Text,Item,Input,Form } from 'native-base';



 
  export default class Home extends Component {
    // static navigationOptions = ({ navigation }) => {
    //   return {
    //     header: <CustomHeader this navigation={navigation} />
    //   };
    // };
    constructor(props) {
      super(props);
      this.state = {
        animate1:false,
        dataSource:[],
        FirstName: "",
        LastName: "",
        BirthDate: "",
        Email: "",
        UserName: "",
        Password: "",
        PasswordConfirmation:"",
        Gender: "",
        PhoneNumber: "",
        MobileCode : "",
        CountryId: "",
        ZipCode:"",
        Address: "",
        DeviceId:"123",
        ReferralCode: "",
        Source: "Direct",
        UserTypeID: '2',
        SourceGUID: "",
        DeviceType: "Native",
        DeviceGUID: "",
        DeviceToken: "",
        IPAddress: "",
        Latitude:"",
        Longitude:"",
        error: {},
        animate:false,
         selectedDate: "",
        isDateTimePickerVisible: false,
        animate2:false,
        isLoading: false,
        isSignInButtonEnable:false,
      };
    }
    // static navigationOptions = {
    //   title: "Welcome"
    // }
    
    render() {
  return (
  
    <Container>
      {/* <StatusBar translucent={true}></StatusBar> */}
    {/* <Header /> */}

       <View>

       </View>
 
        <Content>
         <View style={{alignSelf:'center',width:'90%',marginTop:50}}>

         <Text>sjajsa</Text>
          </View>
       </Content>

    {/* <Footer style={{ backgroundColor:'white',borderColor:'red', height:'20%',width:'98%', alignSelf:'center', borderTopWidth: -50,
 backgroundColor:'red',borderTopRightRadius:20,borderTopLeftRadius:20}}>
      <FooterTab style={{ borderTopWidth: -50, borderTopWidth:5,borderColor:'red', borderTopLeftRadius:20,backgroundColor:'red',width:'94%',borderTopRightRadius:20}} >
        <Button >
          <Text>Footer</Text>
        </Button>
      </FooterTab>
    </Footer> */}
  </Container>


  );
}
};


const styles = StyleSheet.create({
  textInput: {
    minHeight: 28,
    fontSize: 12,
  //  fontFamily: AppFonts.HNMedium,
    backgroundColor: 'transparent',
    color: '#fff',
    width: "90%",
    letterSpacing: 0.9,
    borderRadius:25,
    borderColor:'transparent',
    },
  
});

